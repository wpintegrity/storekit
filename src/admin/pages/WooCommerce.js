import React from 'react';
import woocommerceLogo from '../../../assets/images/woocommerce-logo.svg';
import WooOptions from '../components/WooOptions';
import { Box, Card, Divider } from '@mui/joy';

const WooCommerce = () => {
    return(
        <Card>
            <Box>
                <img src={woocommerceLogo} alt="WooCommerce Logo" width={200} />
            </Box>
            <Divider />
            <WooOptions/>
        </Card>
    );
}

export default WooCommerce;