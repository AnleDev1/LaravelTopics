<?php

namespace App\Http\Controllers;

use App\Models\DetalleOrden;
use App\Models\Orden;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $ordenes = Orden::all();
            // Pasando ordenes a un array
            $response = $ordenes->toArray();
            $i = 0;
            foreach ($ordenes as $o) {
                $response[$i]['user'] = $o->user->toArray();
                $detalle = $o->detalle_ordenes->toArray();
                $f = 0;
                foreach ($o->detalle_ordenes as $det) {
                    $detalle[$f]['producto'] = $det->producto->toArray();
                    $detalle[$f]['producto']['marca'] = $det->producto->marca->toArray();
                    $detalle[$f]['producto']['modelo'] = $det->producto->modelo->toArray();
                    $f++;
                }
                $response[$i]['detalleOrden'] = $detalle;
                $i++;
            }
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $errores = 0;
            //Crear la instacia de ordenes
            $ordenes = new Orden();
            $ordenes->correlativo = $this->getCorrelativo();
            $ordenes->total = $request->total;
            $ordenes->user_id = $request->user['id'];
            if ($ordenes->save() <= 0) {
                $errores++;
            }
            //Insertando los datos de detalle orden
            $detalle = $request->detalleOrden;
            foreach ($detalle as $key => $det) {
                //Creando la instacia de detalle orden
                $detalleOrden = new DetalleOrden;
                $detalleOrden->cantidad = $det['cantidad'];
                $detalleOrden->producto_id = $det['producto']['id'];
                $detalleOrden->orden_id = $ordenes->id;
                if ($detalleOrden->save() <= 0) {
                    $errores++;
                }
            }
            if ($errores == 0) {
                DB::commit();
                return response()->json(['status' => 'ok', 'data' => $ordenes, 'message' => 'Orden registrada'], 201);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'fail', 'data' => null, 'message' => 'Orden no registrada'], 409);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $orden = Orden::findOrFail($id);
            $response = $orden->toArray();
            $response['user'] = $orden->user->toArray();
            $detalle = $orden->detalle_ordenes->toArray();
            $i=0;
            foreach ($orden->detalle_ordenes as $o) {
                $detalle[$i]['producto'] = $o->producto->toArray();
                $detalle[$i]['producto']['marca'] = $o->producto->marca->toArray();
                $detalle[$i]['producto']['modelo'] = $o->producto->modelo->toArray();
                $i++;
            }
            $response['detalleOrden'] = $detalle;
            return $response;
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $orden = Orden::findOrFail($id);
            if ($request->estado  == 'C') {
                $orden->estado = $request->estado;
                if ($orden->update() >= 0) {
                   return response()->json(['status' => 'ok', 'message' => 'Orden cancelada, se ha reembolsado un total de: ' . $orden->total], 201);
                }else{
                    return response()->json(['status' => 'fail', 'message' => 'La orden no pudo ser cancelada, intente de nuevo'], 409);
                }
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    //metodo para generar correlativo
    private function getCorrelativo() {
        $query = 'SELECT CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM ordenes WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))';
        $result = DB::select($query);
        return $result[0]->correlativo;
    }

}
