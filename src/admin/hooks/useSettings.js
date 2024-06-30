import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { toast } from 'react-toastify';

/**
 * useSettings Hook
 * 
 * This custom hook handles fetching, updating, and managing settings and pages for a given endpoint.
 * It utilizes WordPress REST API endpoints to fetch and update settings.
 * 
 * @since 2.0.0
 * 
 * @param {string} endpoint - The endpoint to fetch and update settings from.
 * @param {object} initialSettings - The initial settings to use as defaults.
 * 
 * @returns {object} - An object containing the settings, setSettings function, pages, and updateSettings function.
 */
const useSettings = (endpoint, initialSettings) => {
    const [settings, setSettings] = useState(initialSettings);
    const [pages, setPages] = useState([]);

    /**
     * Fetches settings from the provided endpoint and updates the state.
     * 
     * @since 2.0.0
     * 
     * @returns {void}
     */
    const fetchSettings = useCallback(async () => {
        try {
            const response = await axios.get(`/wp-json/storekit/v1/${endpoint}-settings`, {
                headers: {
                    'X-WP-Nonce': storekitApiSettings.nonce
                }
            });

            if (response.status === 200) {
                const fetchedSettingsArray = response.data[`${endpoint}_settings`];
                const fetchedSettings = fetchedSettingsArray.reduce((acc, setting) => {
                    acc[setting.key] = setting.value;
                    return acc;
                }, {});

                const mergedSettings = { ...initialSettings, ...fetchedSettings };
                setSettings(mergedSettings);
            } else {
                throw new Error(`Failed to fetch settings: ${response.statusText}`);
            }
        } catch (error) {
            console.error('Error fetching settings:', error);
            toast.error('Failed to fetch settings', {
                position: 'bottom-right'
            });
        }
    }, [endpoint, initialSettings]);

    /**
     * Fetches pages from the WordPress REST API and updates the state.
     * 
     * @since 2.0.0
     * 
     * @returns {void}
     */
    const fetchPages = useCallback(async () => {
        try {
            const response = await axios.get('/wp-json/wp/v2/pages', {
                headers: {
                    'X-WP-Nonce': storekitApiSettings.nonce
                }
            });

            if (response.status === 200) {
                setPages(response.data);
            } else {
                throw new Error(`Failed to fetch pages: ${response.statusText}`);
            }
        } catch (error) {
            console.error('Error fetching pages:', error);
        }
    }, []);

    // Fetch settings and pages when the component mounts or when the endpoint changes
    useEffect(() => {
        fetchSettings();
        fetchPages();
    }, [fetchSettings, fetchPages]);

    /**
     * Updates settings by sending a POST request to the provided endpoint.
     * 
     * @since 2.0.0
     * 
     * @param {object} updatedSettings - The settings to be updated.
     * 
     * @returns {Promise<void>}
     */
    const updateSettings = async (updatedSettings) => {
        try {
            const response = await axios.post(`/wp-json/storekit/v1/${endpoint}-settings`, updatedSettings, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': storekitApiSettings.nonce
                }
            });

            if (response.status === 200) {
                setSettings(updatedSettings);
                toast.success('Settings updated successfully', {
                    position: 'bottom-right'
                });
            } else {
                throw new Error(`Failed to update settings: ${response.statusText}`);
            }
        } catch (error) {
            toast.error('Failed to update settings', {
                position: 'bottom-right'
            });
        }
    };

    return { settings, setSettings, pages, updateSettings };
};

export default useSettings;
