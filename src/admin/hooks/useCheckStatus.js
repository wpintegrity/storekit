import { useState, useEffect } from 'react';
import axios from 'axios';

/**
 * useCheckStatus Hook
 * 
 * This hook checks the status of a given type (e.g., plugin) and slug (e.g., 'dokan-lite/dokan.php').
 * It makes a request to a custom REST API endpoint and returns whether the specified item is active.
 * 
 * @since 2.0.0
 * 
 * @param {string} type - The type of item to check (e.g., 'plugin').
 * @param {string} slug - The slug of the item to check (e.g., 'dokan-lite/dokan.php').
 * 
 * @returns {boolean} - Returns `true` if the item is active, otherwise `false`.
 */
const useCheckStatus = (type, slug) => {
    const [isActive, setIsActive] = useState(false);

    useEffect(() => {
        const checkStatus = async () => {
            try {
                const response = await axios.get(`/wp-json/storekit/v1/check_status?type=${type}&slug=${slug}`, {
                    headers: {
                        'X-WP-Nonce': storekitApiSettings.nonce
                    }
                });
                setIsActive(response.data.is_active);
            } catch (error) {
                console.log(`Error checking ${type} status for ${slug}:`, error);
            }
        };

        checkStatus();
    }, [type, slug]);

    return isActive;
}

export default useCheckStatus;
