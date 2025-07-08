<template>
    <div id="map" style="height: 400px; width: 100%"></div>
</template>

<script>
import "leaflet/dist/leaflet.css";
import L from "leaflet";

export default {
    props: {
        pickup: {
            type: Object,
            default: () => ({ lat: -6.2088, lng: 106.8456 }),
        },
        destination: {
            type: Object,
            default: null,
        },
        markers: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            map: null,
            pickupMarker: null,
            destinationMarker: null,
            routeLayer: null,
        };
    },
    mounted() {
        this.initMap();
        this.addPickupMarker();

        if (this.destination) {
            this.addDestinationMarker();
            this.drawRoute();
        }
    },
    methods: {
        initMap() {
            this.map = L.map("map").setView(
                [this.pickup.lat, this.pickup.lng],
                13
            );

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(this.map);
        },
        addPickupMarker() {
            this.pickupMarker = L.marker([this.pickup.lat, this.pickup.lng])
                .addTo(this.map)
                .bindPopup("Lokasi Penjemputan");
        },
        addDestinationMarker() {
            this.destinationMarker = L.marker([
                this.destination.lat,
                this.destination.lng,
            ])
                .addTo(this.map)
                .bindPopup("Lokasi Tujuan");

            // Fit bounds to show both markers
            const group = new L.featureGroup([
                this.pickupMarker,
                this.destinationMarker,
            ]);
            this.map.fitBounds(group.getBounds());
        },
        async drawRoute() {
            if (this.routeLayer) {
                this.map.removeLayer(this.routeLayer);
            }

            try {
                const response = await axios.get(
                    `https://router.project-osrm.org/route/v1/driving/${this.pickup.lng},${this.pickup.lat};${this.destination.lng},${this.destination.lat}?overview=full&geometries=geojson`
                );

                const route = response.data.routes[0];
                this.$emit("distance-calculated", route.distance / 1000); // in km

                this.routeLayer = L.geoJSON({
                    type: "Feature",
                    geometry: route.geometry,
                    properties: {},
                }).addTo(this.map);
            } catch (error) {
                console.error("Error drawing route:", error);
            }
        },
    },
    watch: {
        destination(newVal) {
            if (newVal) {
                this.addDestinationMarker();
                this.drawRoute();
            }
        },
    },
};
</script>
