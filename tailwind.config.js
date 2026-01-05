import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    safelist: [
        // Prioridades
        "bg-red-100",
        "text-red-700",
        "bg-yellow-100",
        "text-yellow-700",
        "bg-green-100",
        "text-green-700",

        // Datas (se aplicável)
        "bg-red-200",
        "bg-yellow-200",
        "bg-green-200",
        "text-red-800",
        "text-yellow-800",
        "text-green-800",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
