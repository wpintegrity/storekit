import React from 'react';
import { Box } from '@mui/joy';

import WooCommerce from '../pages/WooCommerce';
import Dokan from '../pages/Dokan';

const Content = ({ selectedOption }) => {
    return(
        <Box className="storekit-settings-content">
            { selectedOption === 'WooCommerce' && <WooCommerce/> }
            { selectedOption === 'Dokan' && <Dokan/> }
        </Box>
    );
}

export default Content;