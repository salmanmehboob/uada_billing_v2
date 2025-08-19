<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlloteeRequest;
use App\Models\Allotee;
use App\Models\Sector;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Yajra\DataTables\Facades\DataTables;

#[AllowDynamicProperties]
class AlloteeController extends Controller
{
    protected string $route;
    protected string $viewFolder;
    protected string $title;

    public function __construct()
    {
        $this->title = 'Allotee';
        $this->route = 'admin.allotees';
        $this->viewFolder = 'allotees';
    }

    public function index(Request $request)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        // Keep dropdown data for forms; do NOT preload large allotee dataset
        $data['sectors'] = Sector::all();
        $data['sizes'] = Size::all();
        $data['types'] = Type::all();

        return view($this->viewFolder . '.index', $data);
    }

    public function data(Request $request)
    {
        $query = Allotee::query()
            ->leftJoin('sectors', 'sectors.id', '=', 'allotees.sector_id')
            ->leftJoin('sizes', 'sizes.id', '=', 'allotees.size_id')
            ->leftJoin('types', 'types.id', '=', 'allotees.type_id')
            ->select([
                'allotees.id',
                'allotees.plot_no',
                'allotees.name',
                'allotees.phone_no',
                'allotees.sector_id',
                'allotees.size_id',
                'allotees.type_id',
                'allotees.arrears',
                'allotees.created_at',
                DB::raw('COALESCE(sectors.name, "") as sector_name'),
                DB::raw('COALESCE(sizes.name, "") as size_name'),
                DB::raw('COALESCE(types.name, "") as type_name'),
            ]);

        return \Yajra\DataTables\Facades\DataTables::eloquent($query)
            // Global search across ALL visible columns (including joined names)
            ->filter(function ($query) use ($request) {
                $search = $request->input('search.value');
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $like = '%' . $search . '%';
                        $q->where('allotees.id', 'like', $like)
                            ->orWhere('allotees.name', 'like', $like)
                            ->orWhere('allotees.plot_no', 'like', $like)
                            ->orWhere('allotees.email', 'like', $like)
                            ->orWhere('allotees.phone_no', 'like', $like)
                            ->orWhere('allotees.arrears', 'like', $like)
                            ->orWhere('allotees.guardian_name', 'like', $like)
                            ->orWhere('allotees.contact_person_name', 'like', $like)
                            ->orWhere('allotees.address', 'like', $like)
                            ->orWhere('sectors.name', 'like', $like)
                            ->orWhere('sizes.name', 'like', $like)
                            ->orWhere('types.name', 'like', $like);
                    });
                }
            })
            // Make alias columns individually searchable/orderable as well
            ->filterColumn('sector_name', function ($query, $keyword) {
                $query->where('sectors.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('size_name', function ($query, $keyword) {
                $query->where('sizes.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('type_name', function ($query, $keyword) {
                $query->where('types.name', 'like', '%' . $keyword . '%');
            })
            ->orderColumn('sector_name', 'sectors.name $1')
            ->orderColumn('size_name', 'sizes.name $1')
            ->orderColumn('type_name', 'types.name $1')
            ->editColumn('is_active', fn ($row) => $row->is_active ? 'Active' : 'Inactive')
            ->addColumn('actions', function ($row) {
                $attrs = [
                    'type' => 'button',
                    'class' => 'btn btn-icon btn-outline-primary btn-edit',
                    'data-bs-toggle' => 'offcanvas',
                    'data-bs-target' => '#offcanvasEdit',
                    'data-id' => e($row->id),
                    'data-name' => e($row->name),
                    'data-plot_no' => e($row->plot_no),
                    'data-email' => e((string) $row->email),
                    'data-phone_no' => e((string) $row->phone_no),
                    'data-contact_person_name' => e((string) $row->contact_person_name),
                    'data-address' => e((string) $row->address),
                    'data-sector_id' => e((string) $row->sector_id),
                    'data-size_id' => e((string) $row->size_id),
                    'data-type_id' => e((string) $row->type_id),
                    'data-is_active' => e((string) $row->is_active),
                    'data-arrears' => e((string) $row->arrears),
                    'data-guardian_name' => e((string) $row->guardian_name),
                    'data-url' => e(route($this->route . '.update', $row->id)),
                ];
                $attrString = collect($attrs)->map(fn ($v, $k) => $k . '="' . $v . '"')->implode(' ');
                return '<button ' . $attrString . '><i class="icon-base ti tabler-edit icon-22px"></i></button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }



    public function store(AlloteeRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            Allotee::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Allotee created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create allotee: ' . $e->getMessage()]);
        }
    }

    public function edit(Allotee $allotee)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['allotee'] = $allotee;

        return view($this->viewFolder . '.edit', $data);
    }

    public function update(AlloteeRequest $request, Allotee $allotee)
    {
         try {
            DB::beginTransaction();

            $data = $request->validated();
            $allotee->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Allotee updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update allotee: ' . $e->getMessage()]);
        }
    }

    public function destroy(Allotee $allotee)
    {
        try {
            DB::beginTransaction();

//            $allotee->status = 0;
            $allotee->delete();

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Allotee deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete allotee: ' . $e->getMessage()]);
        }
    }
}
