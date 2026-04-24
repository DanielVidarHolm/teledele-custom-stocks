import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Fragment, useState } from '@wordpress/element';
import { Panel, PanelBody, PanelRow, TextControl, ComboboxControl  } from '@wordpress/components';
import { more } from '@wordpress/icons';



import {
    Section,
    SectionHeader,
    Search,
    DropdownButton,
    Pill,
    Spinner,
    DatePicker,
    Table,
} from '@woocommerce/components';

import './style.css';

const headers = [
    {label: "Rule name"},
    {label: "Target category"},
    {label: "Custom message"},
    {label: "Total affected"}
]
const categoryOptions = [
    {
        label: 'Afghanistan',
        value: 'AF'
    },
    {
        label: 'Åland Islands',
        value: 'AX'
    },
]

const rows = [

]

const AdminPage = () => {
    const [ selected, setSelected ] = useState( [] );

    return (
            <Section>


                <TextControl
                    __next40pxDefaultSize
                    label="Rule name"
                    onChange={() => {}}
                    placeholder="Placeholder"
                    value=""
                />

                <ComboboxControl
                    __next40pxDefaultSize
                    label="Category"
                    onChange={() => {}}
                    onFilterValueChange={() => {}}
                    options={categoryOptions}
                    value={null}
                />

                <TextControl
                    __next40pxDefaultSize
                    label="Custom message"
                    onChange={() => {}}
                    placeholder="Placeholder"
                    value=""
                />


                <Table
                    headers={headers}
                />
            </Section>
    );
};

// Register a WooCommerce Admin page route
addFilter(
    'woocommerce_admin_pages_list',
    'teledele-custom-stocks',
    ( pages ) => {
        pages.push( {
            container: AdminPage,
            path: '/teledele-custom-stocks',
            breadcrumbs: [ __( 'Teledele Custom Stocks', 'teledele-custom-stocks' ) ],
            navArgs: { id: 'teledele_custom_stocks' },
        } );

        return pages;
    }
);