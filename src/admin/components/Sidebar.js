import React from 'react';
import { Box } from '@mui/joy';
import Menus from './Menus';

/**
 * Sidebar Component
 * 
 * This component renders the sidebar for the storekit settings. 
 * It includes the Menus component which provides navigation options.
 * 
 * @since 2.0.0
 * 
 * @param {Object} props - Component properties.
 * @param {string} props.selectedOption - The currently selected menu option.
 * @param {Function} props.setSelectedOption - Function to set the selected menu option.
 * 
 * @returns {JSX.Element} The Sidebar component.
 */
const Sidebar = ({ selectedOption, setSelectedOption }) => {
    return (
        <Box className="storekit-settings-sidebar">
            <Menus selectedOption={selectedOption} setSelectedOption={setSelectedOption} />
        </Box>
    );
}

export default Sidebar;
