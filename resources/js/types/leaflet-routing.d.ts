declare module 'leaflet-routing-machine' {
    import * as L from 'leaflet';

    namespace Routing {
        interface RoutingControlOptions {
            waypoints?: L.LatLng[];
            routeWhileDragging?: boolean;
            addWaypoints?: boolean;
            createMarker?: () => null;
            lineOptions?: {
                styles: Array<{
                    color: string;
                    weight: number;
                    opacity: number;
                }>;
            };
            router?: any;
            show?: boolean;
            collapsible?: boolean;
        }

        interface RoutingControl extends L.Control {
            on(event: string, handler: (e: any) => void): this;
        }

        function control(options: RoutingControlOptions): RoutingControl;
        function osrmv1(options: { serviceUrl: string; profile: string }): any;
    }

    module 'leaflet' {
        namespace L {
            const Routing: typeof import('leaflet-routing-machine').Routing;
        }
    }
}
