import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Fragment, useState } from '@wordpress/element';
import { Dropdown } from '@wordpress/components';

import {
    Section,
    SectionHeader,
    Search,
    DropdownButton,
    Pill,
    Spinner,
    DatePicker,
} from '@woocommerce/components';

import './style.css';

const AdminPage = () => {
    const [ selected, setSelected ] = useState( [] );

    return (
        <Fragment>
            <Section component="article">
                <SectionHeader title={ __( 'Search', 'teledele-custom-stocks' ) } />
                <Search
                    type="products"
                    placeholder={ __( 'Search for something', 'teledele-custom-stocks' ) }
                    selected={ selected }
                    onChange={ setSelected }
                    inlineTags
                />
            </Section>

            <Section component="article">
                <SectionHeader title={ __( 'Dropdown', 'teledele-custom-stocks' ) } />
                <Dropdown
                    renderToggle={ ( { isOpen, onToggle } ) => (
                        <DropdownButton
                            onClick={ onToggle }
                            isOpen={ isOpen }
                            labels={ [ __( 'Dropdown', 'teledele-custom-stocks' ) ] }
                        />
                    ) }
                    renderContent={ () => <p>Dropdown content here</p> }
                />
            </Section>

            <Section component="article">
                <SectionHeader
                    title={ __( 'Pill shaped container', 'teledele-custom-stocks' ) }
                />
                <Pill className="pill">
                    { __( 'Pill Shape Container', 'teledele-custom-stocks' ) }
                </Pill>
            </Section>

            <Section component="article">
                <SectionHeader title={ __( 'Spinner', 'teledele-custom-stocks' ) } />
                <p>I am a spinner!</p>
                <Spinner />
            </Section>

            <Section component="article">
                <SectionHeader title={ __( 'Datepicker', 'teledele-custom-stocks' ) } />
                <DatePicker
                    text={ __( 'I am a datepicker!', 'teledele-custom-stocks' ) }
                    dateFormat="MM/DD/YYYY"
                />
            </Section>
        </Fragment>
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