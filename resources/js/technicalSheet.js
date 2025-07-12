import Alpine from "alpinejs";
import * as bootstrap from "bootstrap";
import { initMap } from "./common/googlemaps";
import "swiper/css/bundle";
import lightGallery from "lightgallery";
import lgThumbnail from "lightgallery/plugins/thumbnail";
import lgZoom from "lightgallery/plugins/zoom";
import Quill from "quill";
const baseURL = import.meta.env.VITE_APP_URL;
const AWS_URL_S3 = import.meta.env.VITE_AWS_URL;
window.baseURL = baseURL;
window.AWS_URL_S3 = AWS_URL_S3;
window.bootstrap = bootstrap;
window.Alpine = Alpine;

function initializeWeb() {
    console.info("Developed by: Elias Lopez.");
    console.log("Initialized Portal Cielo.");
}

initializeWeb();

window.addEventListener("load", function () {
    var preloader = document.querySelector(".preloader");
    if (preloader) {
        preloader.style.display = "none";
    }
});

lightGallery(document.querySelector(".lightGallery-property-images"), {
    plugins: [lgThumbnail, lgZoom],
    selector: ".lightGallery-property-images__image",
    speed: 500,
    thumbnail: true,
    zoom: true,
});

Alpine.data("property", (property, resourceCount) => ({
    resourceCount: resourceCount,
    imagesMin: false,
    buttonText: "",
    async init() {
        this.updateButtonText();
        window.addEventListener("resize", () => {
            this.updateButtonText();
        });
        this.loadDescription();
        if (property.latitude && property.longitude) {
            await initMap(
                "property-location-map",
                parseFloat(property.latitude),
                parseFloat(property.longitude)
            );
        }
    },
    loadDescription() {
        const property__description__data = new Quill(
            ".property-description__data",
            {
                theme: "snow",
                readOnly: true,
                modules: {
                    toolbar: false,
                },
            }
        );
        const contenidoDelta = JSON.parse(property.description);
        property__description__data.setContents(contenidoDelta);
    },
    updateButtonText() {
        if (this.resourceCount > 3 && this.resourceCount < 7) {
            this.imagesMin = true;
            this.buttonText = this.resourceCount;
            if (window.innerWidth <= 1399) {
                if (this.resourceCount != 4) {
                    this.imagesMin = false;
                }
                if (window.innerWidth <= 991) {
                    this.imagesMin = false;
                    this.buttonText = "+ " + this.resourceCount;
                } else if (window.innerWidth <= 1299) {
                    this.imagesMin = false;
                    this.buttonText = "+ " + (this.resourceCount - 2);
                } else {
                    this.buttonText = "+ " + (this.resourceCount - 4);
                }
            }
        } else {
            this.imagesMin = false;
            if (window.innerWidth <= 1399) {
                if (window.innerWidth <= 991) {
                    this.buttonText = "+ " + this.resourceCount;
                } else if (window.innerWidth <= 1299) {
                    this.buttonText = "+ " + (this.resourceCount - 2);
                } else {
                    this.buttonText = "+ " + (this.resourceCount - 4);
                }
            } else {
                this.buttonText = "+ " + (this.resourceCount - 6);
            }
        }
    },
}));
Alpine.start();
