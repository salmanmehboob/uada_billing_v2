/**
 * UI Toasts
 */

'use strict';

$(document).ready(function () {
    // Custom Notyf class to allow HTML content in messages
    class CustomNotyf extends Notyf {
        _renderNotification(options) {
            const notification = super._renderNotification(options);
            if (options.message) {
                notification.message.innerHTML = options.message;
            }
            return notification;
        }
    }

    // Initialize Notyf instance
    const notyf = new CustomNotyf({
        duration: 3000, // 3 seconds
        ripple: true, // Enable ripple effect
        dismissible: true, // Allow closing
        position: { x: 'right', y: 'top' }, // Position at the top right
        types: [
            {
                type: 'success',
                background: '#28a745',
                className: 'notyf__success',
                icon: {
                    className: 'icon-base ti tabler-circle-check-filled icon-md text-white',
                    tagName: 'i'
                }
            },
            {
                type: 'warning',
                background: '#ffc107',
                className: 'notyf__warning',
                icon: {
                    className: 'icon-base ti tabler-alert-triangle-filled icon-md text-white',
                    tagName: 'i'
                }
            },
            {
                type: 'error',
                background: '#dc3545',
                className: 'notyf__error',
                icon: {
                    className: 'icon-base ti tabler-xbox-x-filled icon-md text-white',
                    tagName: 'i'
                }
            },
            {
                type: 'info',
                background: '#17a2b8',
                className: 'notyf__info',
                icon: {
                    className: 'icon-base ti tabler-info-circle icon-md text-white',
                    tagName: 'i'
                }
            }
        ]
    });

    // Retrieve Laravel session messages from the global window object
    const messages = window.sessionMessages || {};

    // Display notifications if messages exist
    if (messages.success) {
        notyf.success(messages.success);
    }
    if (messages.warning) {
        notyf.open({ type: 'warning', message: messages.warning });
    }
    if (messages.error) {
        notyf.error(messages.error);
    }
    if (messages.info) {
        notyf.open({ type: 'info', message: messages.info });
    }
});
