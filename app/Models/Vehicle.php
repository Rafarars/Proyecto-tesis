<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Vehicle extends Model
{
    use HasSpatial;

    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'type',
        'capacity',
        'status',
        'current_location',
        'assigned_route',
        'coverage_area',
    ];

    protected $casts = [
        'current_location' => Point::class,
        'assigned_route' => LineString::class,
        'coverage_area' => Polygon::class,
        'capacity' => 'decimal:2',
    ];

    // Scopes para consultas espaciales
    public function scopeWithinRadius($query, Point $center, float $radiusInMeters)
    {
        return $query->whereRaw(
            'ST_Distance_Sphere(current_location, ST_GeomFromText(?)) <= ?',
            [$center->toWkt(), $radiusInMeters]
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Métodos helper para geolocalización
    public function getDistanceToPoint(Point $point): float
    {
        // Usamos una consulta directa para calcular la distancia
        $result = DB::selectOne(
            'SELECT ST_DISTANCE_SPHERE(ST_GeomFromText(?), ST_GeomFromText(?)) as distance',
            [
                $this->current_location->toWkt(),
                $point->toWkt()
            ]
        );

        return (float) $result->distance;
    }

    public function isWithinArea(Polygon $area): bool
    {
        return $area->contains($this->current_location);
    }

    public function updateLocation(float $latitude, float $longitude): void
    {
        $this->current_location = new Point($latitude, $longitude);
        $this->save();
    }
}
