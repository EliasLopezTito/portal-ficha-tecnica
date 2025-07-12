((g) => {
    var h,
        a,
        k,
        p = "The Google Maps JavaScript API",
        c = "google",
        l = "importLibrary",
        q = "__ib__",
        m = document,
        b = window;
    b = b[c] || (b[c] = {});
    var d = b.maps || (b.maps = {}),
        r = new Set(),
        e = new URLSearchParams(),
        u = () =>
            h ||
            (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g)
                    e.set(
                        k.replace(/[A-Z]/g, (t) => "_" + t[0].toLowerCase()),
                        g[k]
                    );
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => (h = n(Error(p + " could not load.")));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a);
            }));
    d[l]
        ? console.warn(p + " only loads once. Ignoring:", g)
        : (d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)));
})({
    key: "AIzaSyCuGDW2UvJlwLGgvByDOu2r4Mn_ftV7bZ4",
    v: "weekly",
});

let map, marker, autocompleteService, placesService;

/**
 * Initializes a Google Map with specified parameters.
 *
 * @param {string|null} mapId - The ID of the HTML element to contain the map. If null, the map is not created.
 * @param {number} [latitude=-12.048139728833375] - The latitude for the map's center.
 * @param {number} [longitude=-77.04680696162961] - The longitude for the map's center.
 * @returns {Promise<void>} A promise that resolves when the map is initialized.
 */
export async function initMap(
    mapId = null,
    latitude = -12.048139728833375,
    longitude = -77.04680696162961,
    draggable = false
) {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const { PlacesService, AutocompleteService } =
        await google.maps.importLibrary("places");

    autocompleteService = new google.maps.places.AutocompleteService();

    if (mapId !== null) {
        const zoom = 18;
        const position = { lat: latitude, lng: longitude };

        map = new Map(document.getElementById(mapId), {
            zoom: zoom,
            center: position,
            mapId: "8e80037a0d7dc9ac",
            disableDefaultUI: true,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_END,
            },
        });

        placesService = new google.maps.places.PlacesService(map);

        const customIconImg = document.createElement("img");
        customIconImg.src = baseURL + "/assets/images/icons/icon2.svg";
        customIconImg.style.width = "60px";
        customIconImg.style.height = "60px";

        marker = new AdvancedMarkerElement({
            map: map,
            position: position,
            title: ".",
            gmpDraggable: draggable,
            content: customIconImg,
        });

        marker.addListener("dragend", (event) => {
            const position = marker.position;
            Alpine.store("googlemaps").latitude = String(position.lat);
            Alpine.store("googlemaps").longitude = String(position.lng);
            map.setCenter(position);
        });
    } else {
        const hiddenMapId = "hidden-map";
        let hiddenMapElement = document.getElementById(hiddenMapId);
        if (!hiddenMapElement) {
            hiddenMapElement = document.createElement("div");
            hiddenMapElement.id = hiddenMapId;
            hiddenMapElement.style.display = "none";
            document.body.appendChild(hiddenMapElement);
        }
        const position = { lat: latitude, lng: longitude };
        map = new Map(hiddenMapElement);
        placesService = new google.maps.places.PlacesService(map);
    }
}

/**
 * Autocomplete a map query using Google Maps Places API.
 *
 * @param {string} query - The search query to autocomplete.
 * @returns {Promise<Array>} A promise that resolves to an array of place predictions.
 * @throws {Error} If no matches are found for the search query.
 */
export function autocompleteMap(query, regions = false) {
    return new Promise((resolve, reject) => {
        const displaySuggestions = function (predictions, status) {
            if (
                status != google.maps.places.PlacesServiceStatus.OK ||
                !predictions
            ) {
                reject(
                    new Error("No se encontraron coincidencias con la búsqueda")
                );
            } else {
                resolve(predictions);
            }
        };
        autocompleteService.getPlacePredictions(
            {
                input: query,
                componentRestrictions: { country: "PE" },
                types: regions ? ["(regions)"] : [],
                //sessionToken: sessionToken,
            },
            displaySuggestions
        );
    });
}

/**
 * Retrieves the details of a place using the Google Maps Places API.
 *
 * @param {string} place_id - The unique identifier of the place to retrieve details for.
 * @returns {Promise<Object>} A promise that resolves with the place details if successful, or rejects with an error if not.
 */
export function getPlaceDetails(place_id) {
    return new Promise((resolve, reject) => {
        const request = {
            placeId: place_id,
            //fields: ["name", "formatted_address", "place_id", "geometry"],
        };
        placesService.getDetails(request, (place, status) => {
            if (
                status === google.maps.places.PlacesServiceStatus.OK &&
                place &&
                place.geometry &&
                place.geometry.location
            ) {
                resolve(place);
            } else {
                reject(new Error("No se pudieron obtener detalles del lugar."));
            }
        });
    });
}

/**
 * Updates the position of the map and marker to the specified latitude and longitude.
 *
 * @param {number} latitude - The latitude to set the map and marker position to.
 * @param {number} longitude - The longitude to set the map and marker position to.
 * @throws {Error} Throws an error if the map position could not be updated.
 */
export function updateMapPosition(latitude, longitude) {
    const position = { lat: latitude, lng: longitude };
    try {
        map.setCenter(position);
        marker.setPosition(position);
    } catch (error) {
        throw new Error("No se pudo actualizar la posición del mapa.");
    }
}
