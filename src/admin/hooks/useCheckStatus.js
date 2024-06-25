import { useState, useEffect } from 'react';
import axios from 'axios';

const useCheckStatus = ( type, slug ) => {
    const [ isActive, setIsActive ] = useState(false);

    useEffect( () => {
        const checkStatus = async () => {
            try {
                const response = await axios.get( `/wp-json/storekit/v1/check_status?type=${type}&slug=${slug}`, {
                    headers: {
                        'X-WP-Nonce': storekitApiSettings.nonce
                    }
                } );
                setIsActive(response.data.is_active);
            } catch (error) {
                console.log( `Error checking ${type} status for ${slug}:`, error );
            }
        };

        checkStatus();
    }, [type, slug])

    return isActive;
}

export default useCheckStatus;