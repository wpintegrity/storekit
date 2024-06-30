import React from 'react';
import { Box } from '@mui/joy';

import WooCommerce from '../pages/WooCommerce';
import Dokan from '../pages/Dokan';

/**
 * Content component
 *
 * Renders the content based on the selected option.
 *
 * @param {Object} props - The component props.
 * @param {string} props.selectedOption - The currently selected option.
 *
 * @return {JSX.Element} The rendered component.
 */
const Content = ({ selectedOption }) => {
    return (
        <Box className="storekit-settings-content">
            {selectedOption === 'WooCommerce' && <WooCommerce />}
            {selectedOption === 'Dokan' && <Dokan />}
        </Box>
    );
};

export default Content;
