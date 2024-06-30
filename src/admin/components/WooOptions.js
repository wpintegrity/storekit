import React, { useMemo } from 'react';
import { 
    Button, 
    CardActions, 
    CardOverflow, 
    FormControl, 
    FormHelperText, 
    FormLabel, 
    Stack, 
    Switch, 
    Divider, 
    Select, 
    Option, 
    Input 
} from '@mui/joy';
import { ToastContainer } from 'react-toastify';
import "react-toastify/dist/ReactToastify.css";
import { __ } from '@wordpress/i18n';

import useSettings from '../hooks/useSettings';
import { handleSwitchChange, handleInputChange, handleSelectChange } from '../utils/inputHandlers';

/**
 * WooOptions Component
 * 
 * This component renders the WooCommerce settings form for the admin panel.
 * It allows administrators to manage various settings related to WooCommerce.
 * 
 * @since 2.0.0
 * 
 * @returns {JSX.Element} The WooOptions component.
 */
const WooOptions = () => {
    // Initial settings for the WooOptions form
    const initialSettings = useMemo(() => ({
        new_customer_registration_email: false,
        clear_cart_button: false,
        default_product_stock: '',
        product_individual_sale: 'no',
        hide_shipping_methods: false,
        terms_conditions: false,
        terms_conditions_page_id: '',
        external_product_new_tab: false,
        manage_profile_avatar: false,
        my_account_admin_menu: true,
    }), []);

    // Hook to manage settings state and provide update functions
    const { settings, setSettings, pages, updateSettings } = useSettings('woocommerce', initialSettings);

    // Handlers for switch, input, and select changes
    const onSwitchChange = handleSwitchChange(setSettings);
    const onInputChange = handleInputChange(setSettings);
    const onSelectChange = handleSelectChange(setSettings);

    /**
     * Handle form submission.
     * 
     * @since 2.0.0
     * 
     * @param {Event} event The form submit event.
     * @returns {void}
     */
    const handleSubmit = (event) => {
        event.preventDefault();
        updateSettings(settings);
    };

    return (
        <form noValidate onSubmit={handleSubmit}>
            {/* New Customer Registration Email setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('New Customer Registration Email', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Get new customers registration email to the admin email', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='new_customer_registration_email'
                    checked={Boolean(settings.new_customer_registration_email)}
                    onChange={onSwitchChange('new_customer_registration_email')}
                />
            </Stack>
            <Divider />

            {/* Clear Cart Button setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Clear Cart Button', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Add a clear cart button on the cart page to empty the entire cart with one click', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='clear_cart_button'
                    checked={Boolean(settings.clear_cart_button)}
                    onChange={onSwitchChange('clear_cart_button')}
                />
            </Stack>
            <Divider />

            {/* Default Product Stock setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Default Product Stock', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Insert default product stock amount', 'storekit') }</FormHelperText>
                </FormControl>
                <Input
                    variant="outlined"
                    size='lg'
                    type='number'
                    name='default_product_stock'
                    value={settings.default_product_stock}
                    onChange={onInputChange}
                    sx={{ width: 180 }}
                />
            </Stack>
            <Divider />

            {/* Product Individual Sale setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Product Individual Sale', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Allow only one item to be bought in a single order', 'storekit') }</FormHelperText>
                </FormControl>
                <Select
                    placeholder={ __('Choose one...', 'storekit') }
                    name='product_individual_sale'
                    value={settings.product_individual_sale}
                    onChange={onSelectChange('product_individual_sale')}
                >
                    <Option value="no">{ __('No', 'storekit') }</Option>
                    <Option value="yes">{ __('Yes', 'storekit') }</Option>
                </Select>
            </Stack>
            <Divider />
            
            {/* Hide Shipping Methods setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Hide Shipping Methods', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Hide other shipping methods when Free Shipping is available on the cart', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='hide_shipping_methods'
                    checked={Boolean(settings.hide_shipping_methods)}
                    onChange={onSwitchChange('hide_shipping_methods')}
                />
            </Stack>
            <Divider />
            
            {/* Terms & Conditions setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Terms & Conditions', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Add Terms & Condition checkbox on the My Account registration form', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='terms_conditions'
                    checked={Boolean(settings.terms_conditions)}
                    onChange={onSwitchChange('terms_conditions')}
                />
            </Stack>
            {settings.terms_conditions && (
                <>
                <Divider />
                
                {/* Terms & Conditions Page selection */}
                <Stack
                    direction={'row'}
                    justifyContent={'space-between'}
                    alignItems={'center'}
                    sx={{ my: 1 }}
                >
                    <FormControl>
                        <FormLabel>{ __('Select Terms & Condition Page', 'storekit') }</FormLabel>
                    </FormControl>
                    <Select
                        placeholder={ __('Choose one...', 'storekit') }
                        name='terms_conditions_page_id'
                        value={settings.terms_conditions_page_id}
                        onChange={onSelectChange('terms_conditions_page_id')}
                    >
                        {pages && pages.map(page => (
                            <Option key={page.id} value={page.id}>{ page.title && page.title.rendered }</Option>
                        ))}
                    </Select>
                </Stack>
                </>
            )}
            <Divider/>

            {/* External Product New Tab setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('External Product New Tab', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Open External/Affiliate Type Products on a new tab', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='external_product_new_tab'
                    checked={Boolean(settings.external_product_new_tab)}
                    onChange={onSwitchChange('external_product_new_tab')}
                />
            </Stack>
            <Divider/>

            {/* Manage Profile Avatar setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Profile Picture', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Allow users to add custom profile picture from the My Account > Account details page', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='manage_profile_avatar'
                    checked={Boolean(settings.manage_profile_avatar)}
                    onChange={onSwitchChange('manage_profile_avatar')}
                />
            </Stack>
            <Divider/>

            {/* My Account Admin Menu setting */}
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('My Account Menu', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Add My Account Page to the Admin Bar Menu', 'storekit') }</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='my_account_admin_menu'
                    checked={Boolean(settings.my_account_admin_menu)}
                    onChange={onSwitchChange('my_account_admin_menu')}
                />
            </Stack>

            {/* Form actions */}
            <CardOverflow
                sx={{
                    borderTop: '1px solid',
                    borderColor: 'divider',
                    mt: 2
                }}
            >
                <CardActions
                    sx={{
                        alignSelf: 'flex-end',
                        p: '15px 0 0'
                    }}
                >
                    <Button type='submit'>{ __('Save Changes', 'storekit') }</Button>
                    <ToastContainer autoClose={2000} />
                </CardActions>
            </CardOverflow>
        </form>
    );
}

export default WooOptions;
