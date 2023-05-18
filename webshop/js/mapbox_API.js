mapboxgl.accessToken = 'pk.eyJ1IjoiamVyb2VucG9zdCIsImEiOiJjbGgxeTBzbmcxOG1zM2hwYzF0Z3Z5ZGh5In0.C_Qnz_dLQazBpquED4Turg';
const map = new mapboxgl.Map({
container: 'map',
// Choose from Mapbox's core styles, or make your own style with Mapbox Studio
style: 'mapbox://styles/mapbox/streets-v12',
center: [-79.4512, 43.6568],
zoom: 13
});

// Add the control to the map.
map.addControl(
new MapboxGeocoder({
accessToken: mapboxgl.accessToken,
mapboxgl: mapboxgl
})
);


