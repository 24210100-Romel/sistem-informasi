<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Support\MenuLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class MenuController extends Controller
{
    public function index(Request $request, string $jenis)
    {
        $rows = Menu::where('jenis', $jenis)->orderBy('urutan')->orderBy('id')->get();
        $by = Menu::groupByParent($rows);
        $edit = $rows->firstWhere('id', (int) $request->query('edit'));

        // Daftar datar berindentasi untuk pilihan "Menu induk".
        $flatTree = [];
        $walk = function (int $pid, int $lv) use (&$walk, &$flatTree, $by) {
            foreach ($by[$pid] ?? [] as $m) {
                $flatTree[] = ['menu' => $m, 'lv' => $lv];
                $walk((int) $m->id, $lv + 1);
            }
        };
        $walk(0, 0);

        return view('admin.menus', [
            'jenis' => $jenis,
            'label' => Menu::JENIS[$jenis],
            'flat' => $jenis === 'layanan',
            'icons' => Menu::IKON,
            'by' => $by,
            'rows' => $rows,
            'edit' => $edit,
            'flatTree' => $flatTree,
            'skip' => $edit ? array_merge([(int) $edit->id], Menu::descendantIds($by, (int) $edit->id)) : [],
        ]);
    }

    public function store(Request $request, string $jenis): RedirectResponse
    {
        Menu::create($this->validated($request, $jenis) + ['jenis' => $jenis]);

        return redirect()->route('admin.menu.index', $jenis)->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, string $jenis, Menu $menu): RedirectResponse
    {
        abort_unless($menu->jenis === $jenis, 404);

        $menu->update($this->validated($request, $jenis, $menu));

        return redirect()->route('admin.menu.index', $jenis)->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(string $jenis, Menu $menu): RedirectResponse
    {
        abort_unless($menu->jenis === $jenis, 404);

        $menu->delete(); // submenu ikut terhapus (cascade)

        return redirect()->route('admin.menu.index', $jenis)->with('success', 'Menu (beserta submenunya) berhasil dihapus.');
    }

    /** Simpan urutan/hierarki hasil seret-dan-lepas (AJAX). */
    public function reorder(Request $request, string $jenis): JsonResponse
    {
        $payload = $request->input('tree');
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        $ids = Menu::where('jenis', $jenis)->pluck('id')->map(fn ($i) => (int) $i)->all();
        $seen = [];
        $updates = [];

        $walk = function ($nodes, ?int $parent, int $depth) use (&$walk, &$seen, &$updates, $ids, $jenis) {
            if (! is_array($nodes) || $depth > 5 || ($jenis === 'layanan' && $depth > 0)) {
                throw new RuntimeException('struktur tidak valid');
            }
            foreach (array_values($nodes) as $i => $n) {
                $id = (int) ($n['id'] ?? 0);
                if (! in_array($id, $ids, true) || isset($seen[$id])) {
                    throw new RuntimeException('menu tidak valid');
                }
                $seen[$id] = true;
                $updates[] = [$id, $parent, $i + 1];
                $walk($n['children'] ?? [], $id, $depth + 1);
            }
        };

        try {
            $walk($payload, null, 0);
            if (count($seen) !== count($ids)) {
                throw new RuntimeException('jumlah menu tidak cocok');
            }
            DB::transaction(function () use ($updates, $jenis) {
                foreach ($updates as [$id, $parent, $order]) {
                    Menu::where('jenis', $jenis)->whereKey($id)->update(['parent_id' => $parent, 'urutan' => $order]);
                }
            });
        } catch (Throwable $e) {
            return response()->json(['ok' => false], 400);
        }

        return response()->json(['ok' => true]);
    }

    private function validated(Request $request, string $jenis, ?Menu $menu = null): array
    {
        $flat = $jenis === 'layanan';
        $request->merge([
            'title' => trim((string) $request->input('title')),
            'url' => trim((string) $request->input('url')) ?: '#',
        ]);

        $v = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'url' => ['required', 'string', 'max:255', function ($a, $value, $fail) {
                if (MenuLink::isUnsafe($value)) {
                    $fail('URL tidak diizinkan.');
                }
            }],
            'parent_id' => ['nullable', 'integer'],
            'ikon' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer'],
        ], [
            'title.required' => 'Judul menu wajib diisi (maksimal 120 karakter).',
            'title.max' => 'Judul menu wajib diisi (maksimal 120 karakter).',
        ]);

        $parent = null;
        if (! $flat && ! empty($v['parent_id'])) {
            $by = Menu::groupByParent(Menu::where('jenis', $jenis)->get());
            $candidate = Menu::where('jenis', $jenis)->find((int) $v['parent_id']);
            $invalid = ! $candidate || ($menu && ((int) $candidate->id === (int) $menu->id
                || in_array((int) $candidate->id, Menu::descendantIds($by, (int) $menu->id), true)));
            if ($invalid) {
                back()->withInput()->withErrors(['parent_id' => 'Menu induk tidak valid.'])->throwResponse();
            }
            $parent = (int) $candidate->id;
        }

        $ikon = null;
        if ($flat) {
            $ikon = array_key_exists($v['ikon'] ?? '', Menu::IKON) ? $v['ikon'] : 'bi-file-earmark-text';
        }

        return [
            'parent_id' => $parent,
            'title' => $v['title'],
            'url' => $v['url'],
            'urutan' => (int) ($v['urutan'] ?? 0),
            'aktif' => $request->boolean('aktif'),
            'ikon' => $ikon,
        ];
    }
}
