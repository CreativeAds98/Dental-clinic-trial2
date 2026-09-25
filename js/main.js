/* ===== Dr.GR Dental & Aesthetics - Main JavaScript ===== */

document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initMobileMenu();
  initScrollAnimations();
  initScrollToTop();
  initSmoothScroll();
  initAppointmentModal();
  initSocialIcons();
  
  // Initialize Lucide icons
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
});


/* --- Navbar Scroll Effect --- */
function initNavbar() {
  const navbar = document.getElementById('navbar') || document.querySelector('header');
  if (!navbar) return;
  
  const handleScroll = () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };
  
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();
}

/* --- Mobile Menu --- */
function initMobileMenu() {
  const hamburger = document.getElementById('hamburger-btn') || document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileOverlay = document.getElementById('mobile-overlay') || document.getElementById('mobile-menu-overlay');
  const closeBtn = document.getElementById('mobile-close-btn') || document.getElementById('close-menu-btn');
  
  if (!hamburger || !mobileMenu) return;
  
  const openMenu = () => {
    mobileMenu.classList.remove('hidden');
    // slight delay or immediate for transition
    setTimeout(() => {
      mobileMenu.classList.add('open');
      if (mobileOverlay) {
        mobileOverlay.classList.remove('hidden');
        mobileOverlay.classList.add('open');
      }
    }, 10);
    document.body.style.overflow = 'hidden';
  };
  
  const closeMenu = () => {
    mobileMenu.classList.remove('open');
    if (mobileOverlay) {
      mobileOverlay.classList.remove('open');
    }
    setTimeout(() => {
      mobileMenu.classList.add('hidden');
      if (mobileOverlay) {
        mobileOverlay.classList.add('hidden');
      }
    }, 300);
    document.body.style.overflow = '';
  };
  
  hamburger.addEventListener('click', (e) => {
    e.preventDefault();
    if (mobileMenu.classList.contains('open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeMenu);
  
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeMenu);
  });
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });
}

/* --- Scroll Animations --- */
function initScrollAnimations() {
  const elements = document.querySelectorAll('.animate-on-scroll, .animate-fade-in, .animate-slide-left, .animate-slide-right');
  
  if (elements.length === 0) return;
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated', 'is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -20px 0px'
  });
  
  elements.forEach(el => observer.observe(el));
}

/* --- Scroll to Top Button --- */
function initScrollToTop() {
  const btn = document.getElementById('scroll-top-btn');
  if (!btn) return;
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 400) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  }, { passive: true });
  
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* --- Smooth Scroll for Anchor Links --- */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (!targetId || targetId === '#') return;
      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
}

/* --- FAQ Accordion --- */
function toggleFAQ(element) {
  const answer = element.nextElementSibling;
  const chevron = element.querySelector('.faq-chevron') || element.querySelector('[data-lucide="chevron-down"]');
  
  // Close all other FAQs
  document.querySelectorAll('.faq-answer.open').forEach(openAnswer => {
    if (openAnswer !== answer) {
      openAnswer.classList.remove('open');
      const prevChevron = openAnswer.previousElementSibling ? openAnswer.previousElementSibling.querySelector('.faq-chevron') : null;
      if (prevChevron) prevChevron.classList.remove('open');
    }
  });
  
  answer.classList.toggle('open');
  if (chevron) chevron.classList.toggle('open');
}

/* --- Form Validation --- */
function validateContactForm(event) {
  event.preventDefault();
  const form = event.target;
  let isValid = true;
  
  form.querySelectorAll('.error-msg').forEach(el => el.remove());
  form.querySelectorAll('.form-input').forEach(el => el.classList.remove('border-red-500'));
  
  const name = form.querySelector('#fullName');
  if (name && name.value.trim().length < 2) {
    showError(name, 'Please enter your full name');
    isValid = false;
  }
  
  const phone = form.querySelector('#phone');
  if (phone && !/^[6-9]\d{9}$/.test(phone.value.trim())) {
    showError(phone, 'Please enter a valid 10-digit mobile number');
    isValid = false;
  }
  
  const email = form.querySelector('#email');
  if (email && email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
    showError(email, 'Please enter a valid email address');
    isValid = false;
  }
  
  const treatment = form.querySelector('#treatment');
  if (treatment && treatment.value === '') {
    showError(treatment, 'Please select a treatment');
    isValid = false;
  }
  
  const consent = form.querySelector('#consent');
  if (consent && !consent.checked) {
    showError(consent.parentElement, 'Please agree to the terms');
    isValid = false;
  }
  
  if (isValid) {
    const successMsg = document.createElement('div');
    successMsg.className = 'mt-4 p-4 bg-green-50 text-green-700 rounded-lg text-sm font-medium border border-green-200';
    successMsg.textContent = '✓ Thank you! Your message has been sent successfully. Our clinic team will reach out to you shortly.';
    form.appendChild(successMsg);
    form.reset();
    
    setTimeout(() => successMsg.remove(), 5000);
  }
  
  return false;
}

function showError(element, message) {
  element.classList.add('border-red-500');
  const errorMsg = document.createElement('p');
  errorMsg.className = 'error-msg text-red-500 text-xs mt-1';
  errorMsg.textContent = message;
  element.parentNode.appendChild(errorMsg);
}

/* --- Treatment Tabs --- */
function switchTab(tabId) {
  if (!tabId) return;

  // Hide all tab contents
  document.querySelectorAll('.tab-content').forEach(tab => {
    tab.classList.remove('active');
    tab.classList.remove('hidden');
    tab.style.display = 'none';
  });

  // Reset all tab buttons
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active', 'bg-navy', 'text-white', 'shadow-md');
    btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
  });

  // Show target tab content
  const targetTab = document.getElementById('tab-' + tabId);
  if (targetTab) {
    targetTab.classList.add('active');
    targetTab.classList.remove('hidden');
    targetTab.style.display = 'block';
  }

  // Highlight target button
  const targetBtn = document.getElementById('btn-' + tabId) || document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
  if (targetBtn) {
    targetBtn.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
    targetBtn.classList.add('active', 'bg-navy', 'text-white', 'shadow-md');
  }

  // Refresh Lucide icons in case any newly visible tab has SVGs
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}

/* --- APPOINTMENT MODAL POPUP --- */
function initAppointmentModal() {
  if (!document.getElementById('appointment-modal')) {
    const modalHTML = `
      <div id="appointment-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform scale-95 transition-all duration-300 border border-slate-100 relative">
          
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-navy via-navy-light to-navy p-6 text-white relative">
            <button type="button" onclick="closeAppointmentModal()" class="absolute top-4 right-4 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition cursor-pointer z-10" aria-label="Close Modal">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center gap-3">
              <img src="assets/images/logo.jpg" alt="Dr.GR Logo" class="w-12 h-12 rounded-full border-2 border-orange bg-white object-cover shadow-sm">
              <div>
                <h3 class="text-xl font-heading font-bold text-white">Book an Appointment</h3>
                <p class="text-xs text-blue-100">Dr.GR Dental & Aesthetics · Coimbatore</p>
              </div>
            </div>
          </div>

          <!-- Modal Body Form -->
          <form id="appointment-modal-form" onsubmit="handleModalSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            <div>
              <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Full Name *</label>
              <input type="text" id="modal-name" required placeholder="Enter your full name" class="form-input">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Phone Number *</label>
                <input type="tel" id="modal-phone" required placeholder="10-digit mobile number" class="form-input">
              </div>
              <div>
                <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Email (Optional)</label>
                <input type="email" id="modal-email" placeholder="Your email address" class="form-input">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Preferred Date *</label>
                <input type="date" id="modal-date" required class="form-input">
              </div>
              <div>
                <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Preferred Time *</label>
                <select id="modal-time" required class="form-input">
                  <option value="">Select Time Slot</option>
                  <option value="Morning (10 AM - 1 PM)">Morning (10 AM - 1 PM)</option>
                  <option value="Afternoon (1 PM - 4 PM)">Afternoon (1 PM - 4 PM)</option>
                  <option value="Evening (4 PM - 8 PM)">Evening (4 PM - 8 PM)</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Treatment Interest *</label>
              <select id="modal-service" required class="form-input">
                <option value="">Select Speciality / Treatment</option>
                <option value="Dental Care (Braces, Implants, Root Canal)">Dental Care (Braces, Implants, Root Canal)</option>
                <option value="Aesthetic & Skin Care (Hydrafacial, Laser, Peels)">Aesthetic & Skin Care (Hydrafacial, Laser, Peels)</option>
                <option value="Hair & PMU Treatments (PRP, Microblading)">Hair & PMU Treatments (PRP, Microblading)</option>
                <option value="General Consultation">General Consultation</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-navy uppercase tracking-wider mb-1">Message / Notes</label>
              <textarea id="modal-message" rows="2" placeholder="Tell us briefly about your query..." class="form-input"></textarea>
            </div>

            <button type="submit" class="w-full btn-primary justify-center py-3 text-base shadow-lg hover:shadow-orange/30">
              Confirm Appointment Booking →
            </button>
            <p class="text-[11px] text-slate-500 text-center">Our clinic receptionist will confirm your slot via Phone / WhatsApp.</p>
          </form>

        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
  }

  // Attach event delegation for all book appointment buttons
  document.addEventListener('click', (e) => {
    const targetBtn = e.target.closest('.btn-book-appointment, a[href="#book-appointment"], a[href="contact.html#book"]');
    const isBookBtn = targetBtn || (e.target.closest('button') && e.target.closest('button').textContent.trim() === 'Book Appointment');
    if (isBookBtn) {
      e.preventDefault();
      openAppointmentModal();
    }
  });

  const modal = document.getElementById('appointment-modal');
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeAppointmentModal();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAppointmentModal();
  });
}

function openAppointmentModal() {
  const modal = document.getElementById('appointment-modal');
  if (!modal) return;
  modal.classList.remove('opacity-0', 'pointer-events-none');
  const card = modal.querySelector('div');
  if (card) {
    card.classList.remove('scale-95');
    card.classList.add('scale-100');
  }
  document.body.style.overflow = 'hidden';
}

function closeAppointmentModal() {
  const modal = document.getElementById('appointment-modal');
  if (!modal) return;
  modal.classList.add('opacity-0', 'pointer-events-none');
  const card = modal.querySelector('div');
  if (card) {
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
  }
  document.body.style.overflow = '';
}

function handleModalSubmit(event) {
  event.preventDefault();
  const name = document.getElementById('modal-name').value;
  const form = document.getElementById('appointment-modal-form');
  
  if (form) {
    form.innerHTML = `
      <div class="p-8 text-center space-y-4">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">✓</div>
        <h3 class="text-2xl font-heading font-bold text-navy">Appointment Requested!</h3>
        <p class="text-sm text-slate-600 leading-relaxed">Thank you, <strong>${name}</strong>! We have received your booking request. Our clinic receptionist will contact you shortly to confirm your appointment time.</p>
        <button type="button" onclick="closeAppointmentModal()" class="btn-primary mx-auto">Close Window</button>
      </div>
    `;
    setTimeout(() => {
      closeAppointmentModal();
    }, 4500);
  }
}

/* --- SOCIAL MEDIA ICONS RENDERING --- */
function initSocialIcons() {
  const socialContainers = document.querySelectorAll('.social-links-container, .follow-us-icons');
  
  const socialSVGs = {
    facebook: `<svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>`,
    instagram: `<svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" fill="none" stroke="currentColor" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="currentColor" stroke-width="2"/></svg>`,
    youtube: `<svg viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.54 5.33A2.78 2.78 0 003.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.54-5.33 29 29 0 00-.54-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"/></svg>`,
    linkedin: `<svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>`,
    twitter: `<svg viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>`
  };

  socialContainers.forEach(container => {
    container.innerHTML = `
      <a href="https://facebook.com" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Facebook">${socialSVGs.facebook}</a>
      <a href="https://instagram.com" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Instagram">${socialSVGs.instagram}</a>
      <a href="https://youtube.com" target="_blank" rel="noopener" class="social-icon-btn" aria-label="YouTube">${socialSVGs.youtube}</a>
      <a href="https://linkedin.com" target="_blank" rel="noopener" class="social-icon-btn" aria-label="LinkedIn">${socialSVGs.linkedin}</a>
      <a href="https://twitter.com" target="_blank" rel="noopener" class="social-icon-btn" aria-label="Twitter">${socialSVGs.twitter}</a>
    `;
  });
}

/* --- DOCTOR PROFILE MODAL SYSTEM --- */
const DOCTORS_PROFILE_DATA = {
  gowseka: {
    name: "Dr. Gowseka Ragunathan",
    role: "Founder & Chief Dental Surgeon | Consultant – Facial Aesthetics | PMU Specialist",
    degrees: "BDS, PGCFI",
    regNo: "TN DCI Reg. No: 30306 | BH-12500/19029-10401",
    image: "assets/images/dr-gowseka.jpg",
    fallback: "assets/images/dr-gowseka.jpg",
    quote: "Leadership in every smile",
    bio: "With a passion for creating healthy smiles and confident lives, Dr. Gowseka Ragunathan combines clinical expertise with a compassionate, patient-first approach. Her focus is on personalised care that enhances both oral health and facial aesthetics, helping you look and feel your absolute best.",
    specialities: ["Smile Designing & Makeovers", "Facial Aesthetics & Anti-Aging", "Permanent Makeup (PMU)", "Laser Dentistry", "Comprehensive Dental Surgery"],
    timings: "Monday - Saturday: 10:00 AM - 7:30 PM",
    serviceValue: "Dental Care (Braces, Implants, Root Canal)"
  },
  kirubashankar: {
    name: "Dr. Kirubashankar",
    role: "Consultant – Oral & Maxillofacial Surgeon",
    degrees: "BDS, MDS",
    regNo: "DCI Registration No: 20321",
    image: "assets/images/dr-kirubashankar.jpg",
    fallback: "assets/images/dr-kirubashankar.jpg",
    quote: "Precision for a better tomorrow",
    bio: "Dr. Kirubashankar provides advanced surgical care with a focus on surgical precision, patient safety, and optimal post-operative comfort. His extensive expertise spans complex impacted tooth extractions, jaw reconstructive procedures, dental implantology, and facial trauma rehabilitation.",
    specialities: ["Oral & Maxillofacial Surgery", "Surgical Tooth Extractions", "Advanced Dental Implants", "Facial Trauma Rehabilitation", "Orthognathic Jaw Correction"],
    timings: "By Prior Consultation & Appointment",
    serviceValue: "Dental Care (Braces, Implants, Root Canal)"
  },
  bharath: {
    name: "Dr. T. Bharath",
    role: "Root Canal Specialist",
    degrees: "BDS, PGD (Endo)",
    regNo: "Reg No: 26404",
    image: "assets/images/dr-bharath.jpg",
    fallback: "assets/images/doctor-male.jpg",
    quote: "Saving natural smiles",
    bio: "Dr. T. Bharath specializes in advanced microscopic endodontics and single-visit root canal treatments. Dedicated to preserving natural tooth structure, he combines painless rotary techniques with modern 3D imaging for high-precision, enduring treatments.",
    specialities: ["Single-Sitting Root Canal", "Microscopic Endodontics", "Root Canal Retreatment", "Pain-Free Dental Care", "Post & Core Restorations"],
    timings: "Consultation on Scheduled Days",
    serviceValue: "Dental Care (Braces, Implants, Root Canal)"
  },
  sindhuja: {
    name: "Dr. Sindhuja",
    role: "Prosthodontist",
    degrees: "BDS, MDS (Prosthodontics)",
    regNo: "Reg No: 32805",
    image: "assets/images/dr-sindhuja.jpg",
    fallback: "assets/images/doctor-female.jpg",
    quote: "Restoring confidence",
    bio: "Dr. Sindhuja is an expert in restoring missing teeth, creating lifelike ceramic veneers, and executing complex full-mouth rehabilitations. Her meticulous attention to aesthetic proportion and dental bite function brings newfound confidence to every patient.",
    specialities: ["Zirconia Crowns & Bridges", "Porcelain Aesthetic Veneers", "Full Mouth Rehabilitation", "Complete & Partial Dentures", "Implant-Supported Dentures"],
    timings: "Consultation on Scheduled Days",
    serviceValue: "Dental Care (Braces, Implants, Root Canal)"
  },
  akshaya: {
    name: "Dr. Akshaya Murugan",
    role: "Oral Medicine & Radiologist",
    degrees: "BDS, MDS (Oral Medicine)",
    regNo: "Reg No: 64179A",
    image: "assets/images/dr-akshaya.jpg",
    fallback: "assets/images/doctor-female.jpg",
    quote: "Diagnosis deeper insights brighter outcomes",
    bio: "Dr. Akshaya Murugan specializes in clinical oral medicine, early detection of oral mucosal conditions, and advanced 3D CBCT/radiographic diagnosis. Her comprehensive evaluations ensure clear insights before initiating specialized treatments.",
    specialities: ["Advanced Radiographic & CBCT Analysis", "Oral Mucosal Disease Management", "TMJ Disorder Diagnosis & Non-Surgical Care", "Oral Pre-Cancer & Cancer Screening"],
    timings: "Consultation on Scheduled Days",
    serviceValue: "General Consultation"
  },
  nandhini: {
    name: "Dr. Nandhini",
    role: "Root Canal Specialist",
    degrees: "BDS, MDS",
    regNo: "Reg No: 33402",
    image: "assets/images/dr-nandhini.jpg",
    fallback: "assets/images/doctor-female.jpg",
    quote: "Gentle care lasting smiles",
    bio: "Known for her soothing chairside manner, Dr. Nandhini provides gentle, precision-driven root canal and restorative treatments. She specializes in creating relaxed experiences for anxious patients while delivering long-lasting oral health.",
    specialities: ["Gentle Root Canal Therapy", "Conservative Restorations", "Pediatric Endodontics", "Aesthetic Tooth Fillings"],
    timings: "Consultation on Scheduled Days",
    serviceValue: "Dental Care (Braces, Implants, Root Canal)"
  },
  bhuvana: {
    name: "Mrs. Bhuvana Anandh",
    role: "Senior Staff Nurse & CRM",
    degrees: "Diploma in Nursing Assistant",
    regNo: "10+ Years of Patient Care Experience",
    image: "assets/images/nurse-bhuvana.jpg",
    fallback: "assets/images/nurse-bhuvana.jpg",
    quote: "Compassion in every visit",
    bio: "A warm and dedicated presence, Mrs. Bhuvana Anandh ensures every patient feels comfortable, supported, and valued from consultation through follow-up. With over a decade in chairside assistance and customer relationship management, she guarantees a reassuring clinical environment.",
    specialities: ["Chairside Patient Comfort", "Pre & Post-Operative Patient Care", "Sterilization Protocols & Clinic Hygiene", "Patient Consultation Scheduling"],
    timings: "Monday - Saturday: 10:00 AM - 8:00 PM",
    serviceValue: "General Consultation"
  }
};

function openDoctorModal(docId) {
  const doc = DOCTORS_PROFILE_DATA[docId];
  if (!doc) return;

  let modal = document.getElementById('doctor-profile-modal');
  if (!modal) {
    const modalMarkup = `
      <div id="doctor-profile-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/75 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 transition-all duration-300 border border-slate-100 relative max-h-[92vh] flex flex-col">
          
          <!-- Header Bar with gradient and close btn -->
          <div class="bg-gradient-to-r from-navy via-[#1e3a5f] to-navy p-6 text-white relative flex-shrink-0">
            <button type="button" onclick="closeDoctorModal()" class="absolute top-4 right-4 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition cursor-pointer z-10" aria-label="Close Modal">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-orange/80 shadow-md flex-shrink-0 bg-slate-100">
                <img id="doc-modal-img" src="" alt="Doctor" class="w-full h-full object-cover">
              </div>
              <div class="pr-8">
                <span class="text-orange text-xs font-bold uppercase tracking-wider block">Specialist Profile</span>
                <h3 id="doc-modal-name" class="text-xl sm:text-2xl font-heading font-bold text-white leading-tight"></h3>
                <p id="doc-modal-role" class="text-xs sm:text-sm text-blue-100 mt-0.5 line-clamp-2"></p>
              </div>
            </div>
          </div>

          <!-- Scrollable Body Content -->
          <div class="p-6 overflow-y-auto space-y-6 flex-1 text-slate-700">
            <!-- Credentials Badges -->
            <div class="flex flex-wrap gap-2.5 pb-2 border-b border-slate-100">
              <span id="doc-modal-degrees" class="inline-flex items-center gap-1.5 bg-orange/10 text-orange font-semibold text-xs px-3 py-1.5 rounded-full border border-orange/20"></span>
              <span id="doc-modal-reg" class="inline-flex items-center gap-1.5 bg-slate-100 text-navy font-medium text-xs px-3 py-1.5 rounded-full border border-slate-200"></span>
            </div>

            <!-- Quote -->
            <div class="bg-amber-50/60 border-l-4 border-orange p-3.5 rounded-r-xl">
              <p id="doc-modal-quote" class="font-cursive text-xl sm:text-2xl text-navy/90 italic"></p>
            </div>

            <!-- About / Bio -->
            <div>
              <h4 class="text-xs font-bold text-navy uppercase tracking-wider mb-2">About the Specialist</h4>
              <p id="doc-modal-bio" class="text-sm leading-relaxed text-slate-600"></p>
            </div>

            <!-- Specialities -->
            <div>
              <h4 class="text-xs font-bold text-navy uppercase tracking-wider mb-2.5">Key Specializations &amp; Clinical Focus</h4>
              <div id="doc-modal-specs" class="flex flex-wrap gap-2"></div>
            </div>

            <!-- Consultation Hours -->
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center gap-3">
              <div class="w-9 h-9 rounded-lg bg-navy/10 text-navy flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <div class="text-xs">
                <p class="font-semibold text-navy">Consultation Hours</p>
                <p id="doc-modal-timings" class="text-slate-500"></p>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row gap-3 items-center justify-between flex-shrink-0">
            <button type="button" onclick="closeDoctorModal()" class="w-full sm:w-auto px-5 py-2.5 text-xs font-semibold text-slate-600 hover:text-navy transition cursor-pointer">
              Close Profile
            </button>
            <button id="doc-modal-book-btn" type="button" class="w-full sm:w-auto btn-primary justify-center text-sm py-2.5 px-6 shadow-md hover:shadow-orange/30">
              Book Consultation With Specialist →
            </button>
          </div>

        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalMarkup);
    modal = document.getElementById('doctor-profile-modal');
    
    // Close on background click
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeDoctorModal();
    });
  }

  // Populate data
  const imgEl = document.getElementById('doc-modal-img');
  imgEl.src = doc.image;
  imgEl.onerror = () => { imgEl.src = doc.fallback || 'assets/images/doctor-male.jpg'; };
  document.getElementById('doc-modal-name').textContent = doc.name;
  document.getElementById('doc-modal-role').textContent = doc.role;
  document.getElementById('doc-modal-degrees').textContent = '🎓 ' + doc.degrees;
  document.getElementById('doc-modal-reg').textContent = '📋 ' + doc.regNo;
  document.getElementById('doc-modal-quote').textContent = `"${doc.quote}"`;
  document.getElementById('doc-modal-bio').textContent = doc.bio;
  document.getElementById('doc-modal-timings').textContent = doc.timings;

  const specsContainer = document.getElementById('doc-modal-specs');
  specsContainer.innerHTML = '';
  doc.specialities.forEach(spec => {
    const pill = document.createElement('span');
    pill.className = 'inline-flex items-center gap-1.5 bg-white text-navy px-3 py-1 rounded-lg text-xs font-medium border border-slate-200 shadow-2xs';
    pill.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-orange"></span>${spec}`;
    specsContainer.appendChild(pill);
  });

  const bookBtn = document.getElementById('doc-modal-book-btn');
  bookBtn.onclick = () => {
    closeDoctorModal();
    setTimeout(() => {
      if (typeof openAppointmentModal === 'function') {
        openAppointmentModal();
        const serviceSelect = document.getElementById('modal-service');
        if (serviceSelect && doc.serviceValue) {
          serviceSelect.value = doc.serviceValue;
        }
        const messageInput = document.getElementById('modal-message');
        if (messageInput) {
          messageInput.value = `Consultation request for ${doc.name}`;
        }
      }
    }, 250);
  };

  // Open modal
  modal.classList.add('open');
  modal.classList.remove('opacity-0', 'pointer-events-none');
  const card = modal.querySelector('div');
  if (card) {
    card.classList.remove('scale-95');
    card.classList.add('scale-100');
  }
  document.body.style.overflow = 'hidden';
}

function closeDoctorModal() {
  const modal = document.getElementById('doctor-profile-modal');
  if (!modal) return;
  modal.classList.remove('open');
  modal.classList.add('opacity-0', 'pointer-events-none');
  const card = modal.querySelector('div');
  if (card) {
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
  }
  document.body.style.overflow = '';
}

