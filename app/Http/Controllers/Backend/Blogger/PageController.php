<?php

namespace App\Http\Controllers\Backend\Blogger;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:page-browse')->only('index');
        $this->middleware('can:page-read')->only('show');
        $this->middleware('can:page-edit')->only('edit', 'update');
        $this->middleware('can:page-add')->only('store', 'create');
        $this->middleware('can:page-delete')->only('destroy');
    }

    private function decryptId(string $id)
    {
        try {
            return decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $pageName = 'Page List';
        if ($request->ajax()) {
            $query = Page::query();
            if (! $request->has('order')) {
                $query->latest();
            }

            return DataTables::eloquent($query)->addIndexColumn()->editColumn('name', function ($row) {
                return '<p class="text-sm font-weight-bold mb-0 text-capitalize">'.$row->name.'</p>';
            })->editColumn('tenant', function ($row) {
                return $row->tenant->name;
            })->editColumn('status', function ($row) {
                return GetStatusBadge($row->status);
            })->editColumn('created_at', function ($row) {
                return $row->created_at->format('d, M Y, H:i A');
            })->addColumn('action', function ($row) {
                $id = encrypt($row->id);
                $btn = '<div class="d-flex">';
                if (Auth::user()->can('page-read') || Auth::user()->can('page-edit')) {
                    $btn .= '<a href="'.route('admin.page.show', $id).'" class="btn btn-subtle-warning m-1 btn-sm"><span class="fas fa-eye"></span></a>';
                }
                if (Auth::user()->can('page-edit')) {
                    $btn .= '<a href="'.route('admin.page.edit', $id).'" class="btn btn-subtle-primary m-1 btn-sm"><span class="fas fa-edit"></span></a>';
                }
                if (Auth::user()->can('page-delete')) {
                    $btn .= '<form method="POST" action="'.route('admin.page.destroy', $id).'" class="m-0 p-0 delete-form">
                '.csrf_field().'
                '.method_field('DELETE').'
                <button type="submit" class="btn btn-subtle-danger m-1 btn-sm confirm-button">
                    <i class="fa fa-trash text-danger"></i>
                </button>
            </form>';
                }
                $btn .= '</div>';

                return $btn;
            })->rawColumns(['name', 'tenant', 'status', 'action'])->make(true);
        }

        return view('backend.page.index', compact('pageName'));
    }

    public function create()
    {
        $pageName = 'Create Page';

        return view('backend.page.create', compact('pageName'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required'],
            'description' => ['nullable'],
        ]);
        try {
            DB::beginTransaction();
            $validated['slug'] = Str::slug($validated['name']);
            Page::create($validated);
            DB::commit();

            return redirect()->route('admin.page.index')->with('success', 'Page created successfully');
        } catch (\Exception $th) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Something went wrong while saving data. '.$th->getMessage());
        }
    }

    public function show(string $id)
    {
        $pageName = 'Page Details';
        $data = Page::findOrFail($this->decryptId($id));

        return view('backend.page.show', [
            'pageName' => $pageName,
            'data' => $data,
        ]);
    }

    public function edit(string $id)
    {
        $pageName = 'Edit Page';
        $data = Page::findOrFail($this->decryptId($id));

        return view('backend.page.edit', compact('pageName', 'data'));
    }

    public function update(Request $request, string $id)
    {
        $page = Page::findOrFail($this->decryptId($id));
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required'],
            'description' => ['nullable'],
        ]);
        try {
            DB::beginTransaction();
            $validated['slug'] = Str::slug($validated['name']);
            $page->update($validated);
            DB::commit();

            return redirect()->route('admin.page.index')->with('success', 'Page updated successfully');
        } catch (\Exception $th) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Something went wrong while saving data. '.$th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $data = Page::findOrFail($this->decryptId($id));
        $data->delete();

        return redirect()
            ->route('admin.page.index')
            ->with('success', 'Page deleted successfully');
    }
}
