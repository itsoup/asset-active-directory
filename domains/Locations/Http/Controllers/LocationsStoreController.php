<?php

namespace Domains\Locations\Http\Controllers;

use App\Http\Controllers\Controller;
use Domains\Locations\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationsStoreController extends Controller
{
    private Location $locations;

    public function __construct(Location $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'parent_id' => [
                'nullable',
                'exists:locations',
            ],
            'customer_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'required',
                'string',
            ],
        ]);

        $this->locations->create([
            'customer_id' => $request->user()->customer_id,
            'parent_id' => $request->input('parent_id'),
            'name' => $request->input('name'),
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
