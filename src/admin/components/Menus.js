import React from 'react';
import { List, ListItem, ListItemButton, ListItemContent } from '@mui/joy';
import useCheckStatus from '../hooks/useCheckStatus';

/**
 * Menus Component
 * 
 * This component renders a list of menu options for the admin panel.
 * It allows administrators to select between different options such as WooCommerce and Dokan.
 * 
 * @since 2.0.0
 * 
 * @param {Object} props - Component properties.
 * @param {string} props.selectedOption - The currently selected menu option.
 * @param {Function} props.setSelectedOption - Function to set the selected menu option.
 * 
 * @returns {JSX.Element} The Menus component.
 */
const Menus = ({ selectedOption, setSelectedOption }) => {
    // Check if the Dokan plugin is active
    const isDokanActive = useCheckStatus('plugin', 'dokan-lite/dokan.php');

    return (
        <List
            size='lg'
            sx={{ gap: 1 }}
        >
            {/* WooCommerce Menu Item */}
            <ListItem>
                <ListItemButton
                    selected={selectedOption === 'WooCommerce'}
                    onClick={() => setSelectedOption('WooCommerce')}
                >
                    <ListItemContent>WooCommerce</ListItemContent>
                </ListItemButton>
            </ListItem>

            {/* Dokan Menu Item (only if the Dokan plugin is active) */}
            {isDokanActive && (
                <ListItem>
                    <ListItemButton
                        selected={selectedOption === 'Dokan'}
                        onClick={() => setSelectedOption('Dokan')}
                    >
                        <ListItemContent>Dokan</ListItemContent>
                    </ListItemButton>
                </ListItem>
            )}
        </List>
    );
}

export default Menus;
