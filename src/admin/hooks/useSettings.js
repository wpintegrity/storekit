import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { toast } from 'react-toastify';

const useSettings = (endpoint, initialSettings) => {
    const [settings, setSettings] = useState(initialSettings);
    const [pages, setPages] = useState([]);

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

    useEffect(() => {
        fetchSettings();
        fetchPages();
    }, [fetchSettings, fetchPages]);

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
