import React from 'react';
import { List, ListItem, ListItemButton, ListItemContent } from '@mui/joy';
import useCheckStatus from '../hooks/useCheckStatus';

const Menus = ({ selectedOption, setSelectedOption }) => {
    const isDokanActive = useCheckStatus( 'plugin', 'dokan-lite/dokan.php' );

    return (
        <List
            size='lg'
            sx={{
                gap: 1
            }}
        >
            <ListItem>
                <ListItemButton
                    selected={ selectedOption === 'WooCommerce' }
                    onClick={ () => setSelectedOption( 'WooCommerce' ) }
                >
                    <ListItemContent>WooCommerce</ListItemContent>
                </ListItemButton>
            </ListItem>
            { isDokanActive && (
                <ListItem>
                    <ListItemButton
                        selected={ selectedOption === 'Dokan' }
                        onClick={ () => setSelectedOption( 'Dokan' ) }
                    >
                        <ListItemContent>Dokan</ListItemContent>
                    </ListItemButton>
                </ListItem>
            ) }
        </List>
    );
}

export default Menus;