function handleThemeChange(mediaQuery) {
    if (mediaQuery.matches) {
        import('primevue/resources/themes/mdc-dark-indigo/theme.css');
    } else {
        import('primevue/resources/themes/mdc-light-indigo/theme.css');
    }
}

const colorSchemeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

colorSchemeMediaQuery.addEventListener("change", () => {
    handleThemeChange(colorSchemeMediaQuery);
});

handleThemeChange(colorSchemeMediaQuery);

export default handleThemeChange
