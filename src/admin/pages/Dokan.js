import React from 'react';
import dokanLogo from '../../../assets/images/dokan-logo.svg';
import DokanOptions from '../components/DokanOptions';
import { Box, Card, Divider } from '@mui/joy';

/**
 * Dokan Component
 * 
 * This component displays the Dokan logo and renders the DokanOptions component.
 * 
 * @since 2.0.0
 * 
 * @returns {JSX.Element} The Dokan component.
 */
const Dokan = () => {
    return (
        <Card>
            <Box>
                <img src={dokanLogo} alt="Dokan Logo" width={180} />
            </Box>
            <Divider />
            <DokanOptions />
        </Card>
    );
}

export default Dokan;
