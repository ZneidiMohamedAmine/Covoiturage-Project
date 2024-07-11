import React from 'react' ;
import { Controller } from '@hotwired/stimulus';

export default function  Controller() {
    const handleSubmit = (e) => {
        e.prevent.default();

        const formData = new formData(e.Target);
        const content = fromData.get('content');


        
    }
    return (
        <div>
            <h1>TEST</h1>
        </div>
    )
}

