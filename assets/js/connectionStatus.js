import { showConfirmModal } from './confirmModal.js';

const ConnectionStatus = {
  icon: null,
  intervalId: null,
  config: {
    lang: {},
  },

  init: function (config) {
    if (!config || !config.lang) {
      console.error('Language configuration not provided to ConnectionStatus.');
      return;
    }
    this.config = config;

    this.icon = $('#connection-status-icon');
    if (this.icon.length === 0) {
      console.error('Connection status icon not found.');
      return;
    }
    this.startPolling();
  },

  startPolling: function () {
    this.ping();
    this.intervalId = setInterval(() => this.ping(), 60000);
  },

  ping: function () {
    $.ajax({
      url: window.site_url + 'api/connection/ping',
      method: 'GET',
      timeout: 30000,
      success: (response) => {
        if (response.status === 'ok') {
          this.setConnected();
        } else {
          this.setDisconnected();
        }
      },
      error: () => {
        this.setDisconnected();
      },
    });
  },

  setConnected: function () {
    if (this.icon.hasClass('text-danger')) {
      notifier.show(this.config.lang.connection_restored, 'success');
    }
    this.icon
      .removeClass('text-danger')
      .addClass('text-success')
      .attr('title', this.config.lang.connection_active);
  },

  setDisconnected: function () {
    if (this.icon.hasClass('text-danger')) {
      return;
    }

    this.icon
      .removeClass('text-success')
      .addClass('text-danger')
      .attr('title', this.config.lang.connection_lost_title);

    notifier.show(this.config.lang.session_lost, 'danger');

    /* temporarily on hold 
    clearInterval(this.intervalId);

    showConfirmModal(
      this.config.lang.connection_lost_reconnect,
      this.config.lang.connection_lost_title
    )
      .then(() => {
        window.location.href = window.site_url + 'logout';
      })
      .catch(() => {
        console.log('User opted to ignore the connection loss warning.');
        this.intervalId = setInterval(() => this.ping(), 60000);
      });
    */
  },
};

export default ConnectionStatus;
