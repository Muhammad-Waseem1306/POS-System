export const posSelectProps = {
    classNamePrefix: "pos-select",
    menuPortalTarget: typeof document !== "undefined" ? document.body : null,
    menuPosition: "fixed",
    styles: {
        menuPortal: (base) => ({ ...base, zIndex: 9999 }),
    },
};
