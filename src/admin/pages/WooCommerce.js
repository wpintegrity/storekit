import React from 'react';
import woocommerceLogo from '../../../assets/images/woocommerce-logo.svg';
import WooOptions from '../components/WooOptions';
import { Box, Card, Divider } from '@mui/joy';

/**
 * WooCommerce Component
 * 
 * This component displays the WooCommerce logo and renders the WooOptions component.
 * 
 * @since 2.0.0
 * 
 * @returns {JSX.Element} The WooCommerce component.
 */
const WooCommerce = () => {
    return (
        <Card>
            <Box>
                <img src={woocommerceLogo} alt="WooCommerce Logo" width={200} />
            </Box>
            <Divider />
            <WooOptions />
        </Card>
    );
}

export default WooCommerce;
