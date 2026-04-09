import "./bootstrap";
import Alpine from "alpinejs";

// NProgress
import NProgress from "nprogress";
import "nprogress/nprogress.css";
import "../css/nprogress.css";

NProgress.configure({
    showSpinner: false,
    speed: 500,
    minimum: 0.1,
});

window.Alpine = Alpine;
window.NProgress = NProgress;

// Global NProgress for Axios
if (window.axios) {
    window.axios.interceptors.request.use(
        (config) => {
            NProgress.start();
            return config;
        },
        (error) => {
            NProgress.done();
            return Promise.reject(error);
        },
    );

    window.axios.interceptors.response.use(
        (response) => {
            NProgress.done();
            return response;
        },
        (error) => {
            NProgress.done();
            return Promise.reject(error);
        },
    );
}

NProgress.start();
Alpine.start();

// Custom Scrollbar
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        NProgress.done();
    }, 200);

    let scrollTimer;
    const html = document.documentElement;
    window.addEventListener(
        "scroll",
        () => {
            html.classList.add("is-scrolling");
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                html.classList.remove("is-scrolling");
            }, 1500);
        },
        { passive: true },
    );
});
