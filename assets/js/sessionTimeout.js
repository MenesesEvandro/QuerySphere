const SessionTimeout = {
  timeout: null,
  warningTimeout: null,
  logoutTimer: null,
  warningModal: null,
  countdownElement: null,
  stayLoggedInBtn: null,
  config: {
    timeoutMinutes: 15,
    warningSeconds: 30,
    logoutUrl: 'logout',
    lang: {},
  },

  init: function (config) {
    if (!config.enabled) {
      return;
    }

    this.config.timeoutMinutes = config.timeoutMinutes;
    this.config.warningSeconds = config.warningSeconds;
    this.config.logoutUrl = config.logoutUrl;
    this.config.lang = config.lang;

    this.warningModal = new bootstrap.Modal(
      document.getElementById('sessionTimeoutModal')
    );
    this.countdownElement = document.getElementById('sessionTimeoutCountdown');
    this.stayLoggedInBtn = document.getElementById('stayLoggedInBtn');

    this.attachEvents();
    this.resetTimer();
  },

  attachEvents: function () {
    window.addEventListener('mousemove', () => this.resetTimer());
    window.addEventListener('keydown', () => this.resetTimer());
    window.addEventListener('click', () => this.resetTimer());

    this.stayLoggedInBtn.addEventListener('click', () => {
      this.warningModal.hide();
      this.resetTimer();
      clearInterval(this.logoutTimer);
    });
  },

  resetTimer: function () {
    clearTimeout(this.timeout);
    this.timeout = setTimeout(
      () => this.showWarningModal(),
      this.config.timeoutMinutes * 60 * 1000
    );
  },

  showWarningModal: function () {
    document.getElementById('sessionTimeoutModalLabel').textContent =
      this.config.lang.title;
    document.getElementById('sessionTimeoutModalBody').textContent =
      this.config.lang.message;

    document.getElementById('stayLoggedInBtn').textContent =
      this.config.lang.stayConnected;

    let countdown = this.config.warningSeconds;
    this.countdownElement.textContent = this.config.lang.countdown.replace(
      '{0}',
      countdown
    );
    this.warningModal.show();

    this.logoutTimer = setInterval(() => {
      countdown--;
      this.countdownElement.textContent = this.config.lang.countdown.replace(
        '{0}',
        countdown
      );
      if (countdown <= 0) {
        clearInterval(this.logoutTimer);
        this.logout();
      }
    }, 1000);
  },

  logout: function () {
    window.location.href = this.config.logoutUrl;
  },
};

export default SessionTimeout;
