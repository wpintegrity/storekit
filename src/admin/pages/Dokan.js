import React from 'react';
import dokanLogo from '../../../assets/images/dokan-logo.svg';
import DokanOptions from '../components/DokanOptions';
import { Box, Card, Divider } from '@mui/joy';

const Dokan = () => {
    return(
        <Card>
            <Box>
                <img src={dokanLogo} alt="Dokan Logo" width={180} />
            </Box>
            <Divider />
            <DokanOptions/>
        </Card>
    );
}

export default Dokan;