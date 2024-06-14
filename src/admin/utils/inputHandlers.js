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

export const handleInputChange = (setSettings) => (event) => {
    const { name, value } = event.target;
    setSettings(prevSettings => ({
        ...prevSettings,
        [name]: value
    }));
};

export const handleSelectChange = (setSettings) => (name) => (event, newValue) => {
    setSettings(prevSettings => ({
        ...prevSettings,
        [name]: newValue
    }));
};
