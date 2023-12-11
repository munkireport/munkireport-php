import type jquery from "jquery"
import type axios from "axios"
import type popper from "popper.js"

declare global {
    interface Window {
        $: jquery;
        jQuery: jquery;
        axios: axios;
        Popper: popper;
    }
}
