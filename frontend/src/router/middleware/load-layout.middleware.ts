const defaultLayout = 'EmptyLayout';

/**
 * Loads and assigns a layout component to the given route's metadata.
 *
 * This function dynamically imports the layout component based on the route's
 * `meta.layout` property. If the specified layout is not found, it falls back
 * to a default layout. It also assigns any provided props to the layout properties.
 *
 * @param route - The route object containing metadata about the layout.
 * @param route.meta - The metadata for the route.
 * @param route.meta.layout - The name of the layout to be used.
 * @param [route.meta.layoutComponent] - The dynamically imported layout component.
 * @param [route.meta.layoutProps] - The props to be passed to the layout component.
 * @return A promise that resolves when the layout has been loaded and attached to the route.
 */
export async function loadLayoutMiddleware(route) {
    try {
        const layout = route.meta.layout;
        const layoutComponent = await import(`@/layouts/${layout}.vue`);
        route.meta.layoutComponent = layoutComponent.default;
    } catch {
        // ignore
        const layoutComponent = await import(`@/layouts/${defaultLayout}.vue`);
        route.meta.layoutComponent = layoutComponent.default;
    }
}
