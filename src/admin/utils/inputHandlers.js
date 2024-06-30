/**
 * Handle switch change events.
 * 
 * This function returns an event handler for switch changes. It can handle both nested and non-nested states.
 * 
 * @since 2.0.0
 * 
 * @param {Function} setSettings - Function to update the settings state.
 * 
 * @returns {Function} - An event handler for switch changes.
 */
export const handleSwitchChange = (setSettings) => (parentKey, childKey) => (event) => {
    const checked = event.target.checked;

    if (childKey) {
        // For nested state
        setSettings(prevSettings => ({
            ...prevSettings,
            [parentKey]: {
                ...prevSettings[parentKey],
                [childKey]: checked
            }
        }));
    } else {
        // For non-nested state
        setSettings(prevSettings => ({
            ...prevSettings,
            [parentKey]: checked
        }));
    }
};

/**
 * Handle input change events.
 * 
 * This function returns an event handler for input changes.
 * 
 * @since 2.0.0
 * 
 * @param {Function} setSettings - Function to update the settings state.
 * 
 * @returns {Function} - An event handler for input changes.
 */
export const handleInputChange = (setSettings) => (event) => {
    const { name, value } = event.target;
    setSettings(prevSettings => ({
        ...prevSettings,
        [name]: value
    }));
};

/**
 * Handle select change events.
 * 
 * This function returns an event handler for select changes.
 * 
 * @since 2.0.0
 * 
 * @param {Function} setSettings - Function to update the settings state.
 * 
 * @returns {Function} - An event handler for select changes.
 */
export const handleSelectChange = (setSettings) => (name) => (event, newValue) => {
    setSettings(prevSettings => ({
        ...prevSettings,
        [name]: newValue
    }));
};
