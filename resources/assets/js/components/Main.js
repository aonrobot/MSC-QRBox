import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import {FilePond, File, registerPlugin } from 'react-filepond';
import FilePondImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

import fontawesome from '@fortawesome/fontawesome';
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import brands from '@fortawesome/fontawesome-free-brands';
import solids from '@fortawesome/fontawesome-free-solid';

import Header from './Header';

fontawesome.library.add(brands, solids);
registerPlugin(FilePondImagePreview);

export default class Main extends Component {
    
    render() {
        let token = document.head.querySelector('meta[name="csrf-token"]').content;
        let label = 'Drag & Drop ไฟล์ของคุณ หรือ <span class="filepond--label-action"> กด Browse ที่นี่ </span>';
        return (
            <div className="h-100">
                <Header />
                {this.props.children}
            </div>
        );
    }
}
