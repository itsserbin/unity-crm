function handleThemeChange(mediaQuery) {
    if (mediaQuery.matches) {
        import('primevue/resources/themes/vela-blue/theme.css');
    } else {
        import('primevue/resources/themes/viva-light/theme.css');
    }
}

const colorSchemeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

colorSchemeMediaQuery.addEventListener("change", () => {
    handleThemeChange(colorSchemeMediaQuery);
});

handleThemeChange(colorSchemeMediaQuery);

export default handleThemeChange
