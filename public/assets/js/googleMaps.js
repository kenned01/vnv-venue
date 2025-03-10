function initMap() {
    document.querySelectorAll("[data-places]").forEach(elm => {
        new google.maps.places.Autocomplete(elm)
    });
}