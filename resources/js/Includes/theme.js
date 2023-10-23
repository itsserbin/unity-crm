function handleThemeChange(mediaQuery) {
    // import('primevue/resources/themes/arya-blue/theme.css');
    // if (mediaQuery.matches) {
    //     import('primevue/resources/themes/arya-blue/theme.css');
    // } else {
    //     import('primevue/resources/themes/mdc-light-indigo/theme.css');
    // }
}

const colorSchemeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

colorSchemeMediaQuery.addEventListener("change", () => {
    handleThemeChange(colorSchemeMediaQuery);
});

handleThemeChange(colorSchemeMediaQuery);

export default handleThemeChange
