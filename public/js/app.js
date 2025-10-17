// app.js - interactive bits for landing page

document.addEventListener('DOMContentLoaded', function () {
  // show year
  document.getElementById('year').textContent = new Date().getFullYear();

  // mobile menu toggle
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  mobileBtn && mobileBtn.addEventListener('click', () => {
    const visible = mobileMenu.style.display === 'flex';
    mobileMenu.style.display = visible ? 'none' : 'flex';
    mobileMenu.setAttribute('aria-hidden', visible ? 'true' : 'false');
  });

  // demo toast helper
  function showToast(message, duration = 3000) {
    const t = document.getElementById('toast');
    t.textContent = message;
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, duration);
  }

  // contact form (mock)
  const form = document.getElementById('contactForm');
  const status = document.getElementById('formStatus');

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = form.name.value.trim();
      const email = form.email.value.trim();
      const message = form.message.value.trim();

      if (!name || !email || !message) {
        status.textContent = 'Please complete all required fields.';
        return;
      }

      status.textContent = 'Sending...';
      // Mock async send
      setTimeout(() => {
        status.textContent = '';
        form.reset();
        showToast('Message sent — we will contact you soon.');
      }, 900);
    });
  }

  // tiny demo update to the mock balance when "Deposit" arrives (for demo only)
  // simulate an inbound deposit event after 4 seconds
  setTimeout(() => {
    const demoBalance = document.getElementById('demoBalance');
    if (!demoBalance) return;
    // parse current balance
    const cur = parseFloat(demoBalance.textContent.replace(/,/g, '')) || 0;
    const incoming = 3000;
    const newBal = (cur + incoming).toFixed(2);
    demoBalance.textContent = newBal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    showToast('New deposit: ₦3,000');
  }, 4000);
});
