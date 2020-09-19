<?php

use Domains\Locations\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Location::class, 'parent_id')->nullable()->index();
            $table->bigInteger('customer_id')->index();
            $table->string('name')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
