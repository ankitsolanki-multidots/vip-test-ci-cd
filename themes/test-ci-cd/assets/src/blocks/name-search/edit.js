/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the server side render element.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-server-side-render/
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * React hook that is used to mark the block wrapper element.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/
 */
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';

/**
 * React hook that is used to mark the components element.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-components/
 */
import {
	PanelBody,
	TextControl,
	RangeControl,
	Button,
	ResponsiveWrapper,
} from '@wordpress/components';

/**
 * React hook that is used to mark the packages element.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/
 */
import { Fragment } from '@wordpress/element';

import metadata from './block.json';
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param {Object}   root0               - The root object.
 * @param {Object}   root0.attributes    - The attributes of the root object.
 * @param {Function} root0.setAttributes - Function to set the attributes.
 * @param {string}   root0.className     - The class name.
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes, className } ) {
	const {
		backgroundImageUrl,
		backgroundImageId,
		postsPerPage,
		buttonLabel,
		previewTerm,
	} = attributes;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Block Settings', 'test-ci-cd' ) }>
					<TextControl
						label={ __( 'Preview search term', 'test-ci-cd' ) }
						help={ __(
							'Used in the editor preview when no live search query is present.',
							'test-ci-cd'
						) }
						placeholder={ __( 'e.g. Woodward', 'test-ci-cd' ) }
						value={ previewTerm }
						onChange={ ( value ) =>
							setAttributes( { previewTerm: value } )
						}
					/>
					<TextControl
						label={ __( 'Search button label', 'test-ci-cd' ) }
						value={ buttonLabel }
						onChange={ ( value ) =>
							setAttributes( { buttonLabel: value } )
						}
					/>
					<RangeControl
						label={ __( 'Results per page', 'test-ci-cd' ) }
						value={ postsPerPage }
						onChange={ ( value ) =>
							setAttributes( { postsPerPage: value } )
						}
						min={ 4 }
						max={ 40 }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Hero Background', 'test-ci-cd' ) }>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) =>
								setAttributes( {
									backgroundImageId: media.id,
									backgroundImageUrl: media.url,
								} )
							}
							allowedTypes={ [ 'image' ] }
							value={ backgroundImageId }
							render={ ( { open } ) => (
								<div>
									{ ! backgroundImageUrl && (
										<Button
											variant="secondary"
											onClick={ open }
										>
											{ __(
												'Select background image',
												'test-ci-cd'
											) }
										</Button>
									) }
									{ backgroundImageUrl && (
										<div>
											<ResponsiveWrapper
												naturalWidth={ 870 }
												naturalHeight={ 300 }
											>
												<img
													src={ backgroundImageUrl }
													alt=""
												/>
											</ResponsiveWrapper>
											<Button
												variant="secondary"
												onClick={ open }
												style={ { marginTop: '8px' } }
											>
												{ __(
													'Replace image',
													'test-ci-cd'
												) }
											</Button>
											<Button
												variant="link"
												isDestructive
												onClick={ () =>
													setAttributes( {
														backgroundImageId: 0,
														backgroundImageUrl: '',
													} )
												}
											>
												{ __(
													'Remove image',
													'test-ci-cd'
												) }
											</Button>
										</div>
									) }
								</div>
							) }
						/>
					</MediaUploadCheck>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block={ metadata.name }
				className={ className }
				attributes={ {
					backgroundImageUrl,
					backgroundImageId,
					postsPerPage,
					buttonLabel,
					previewTerm,
				} }
			/>
		</Fragment>
	);
}
