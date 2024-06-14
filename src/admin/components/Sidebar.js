import React from 'react';
import { Box } from '@mui/joy';
import Menus from './Menus';

const Sidebar = ({ selectedOption, setSelectedOption }) => {
    return(
        <Box className="storekit-settings-sidebar">
            <Menus selectedOption={selectedOption} setSelectedOption={setSelectedOption} />
        </Box>
    );
}

export default Sidebar;