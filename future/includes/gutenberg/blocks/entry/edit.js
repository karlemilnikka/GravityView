import { __, _x } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Panel, PanelBody, Spinner } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import ViewSelector from 'shared/js/view-selector';
import EntrySelector from 'shared/js/entry-selector';

import './editor.scss';

export default function Edit( { attributes, setAttributes, name: blockName } ) {
	const {
		viewId,
		entryId,
		blockPreview
	} = attributes;

	const shouldPreview = ( viewId && entryId );

	const selectFromBlockControlsLabel = _x( 'Please select [control] from the block controls.', '[control] placeholder should not be translated and will be replaced with "an Entry ID" or "a View ID" text.', 'gk-gravityview' );

	const previewImage = gkGravityViewBlocks[ blockName ]?.previewImage;

	const showBlockPreviewImage = () => <img className="gk-gravityview-block block-preview" src={ previewImage } alt={ __( 'Block preview image.', 'gk-gravityview' ) } />;

	if ( blockPreview && previewImage ) {
		return showBlockPreviewImage();
	}

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Main Settings', 'gk-gravityview' ) } initialOpen={ true }>
						<ViewSelector
							viewId={ viewId }
							onChange={ ( viewId ) => { setAttributes( { viewId, entryId: '' } ); } }
						/>

						{ viewId && <>
							<EntrySelector
								entryId={ entryId }
								onChange={ ( entryId ) => { setAttributes( { entryId } ); } }
							/>
						</> }
					</PanelBody>
				</Panel>
			</InspectorControls>

			{ !shouldPreview && <>
				<div className="gk-gravityview-block shortcode-preview">
					{ previewImage && showBlockPreviewImage() }

					<div className="field-container">
						{ !viewId && <p>{ selectFromBlockControlsLabel.replace( '[control]', __( 'a View ID', 'gk-gravityview' ) ) }</p> }
						{ ( viewId && !entryId ) && <p>{ selectFromBlockControlsLabel.replace( '[control]', __( 'an Entry ID', 'gk-gravityview' ) ) }</p> }
					</div>
				</div>
			</> }

			{ shouldPreview && <>
				<ServerSideRender
					LoadingResponsePlaceholder={ () => <>
						{ __( 'Rendering preview...', 'gk-gravityview' ) }
						<Spinner />
					</> }
					block={ blockName }
					attributes={ attributes }
				/>
			</> }
		</div>
	);
}
