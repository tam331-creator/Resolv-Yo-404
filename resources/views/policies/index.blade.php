@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Page Header & Quick Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-100 flex items-center gap-3">
                <i class="fa-solid fa-book-bookmark text-cyan-400"></i> Policies & Knowledge Base
            </h1>
            <p class="text-sm text-slate-400 mt-1">Guidelines, company IT policies, and step-by-step user manuals.</p>
        </div>

        <!-- Quick Search Bar -->
        <div class="w-full md:w-80 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
            <input type="text" id="policySearchInput" onkeyup="searchPolicies()" placeholder="Search policy or guide (e.g. ticket, wifi)..." class="w-full bg-slate-900/90 text-slate-200 text-xs rounded-xl pl-9 pr-3 py-2.5 border border-slate-700/80 outline-none focus:border-cyan-500 transition shadow-inner">
        </div>
    </div>

    <!-- 4 Interactive Category Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div onclick="openCategory('guide')" class="bg-slate-900/80 hover:bg-slate-800/80 border border-slate-700/70 p-4 rounded-xl shadow-lg cursor-pointer transition flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center text-lg border border-sky-500/30 shrink-0 group-hover:scale-105 transition">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-100">System User Guide</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">How to use helpdesk system</p>
            </div>
        </div>

        <div onclick="openCategory('it-policy')" class="bg-slate-900/80 hover:bg-slate-800/80 border border-slate-700/70 p-4 rounded-xl shadow-lg cursor-pointer transition flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-lg border border-indigo-500/30 shrink-0 group-hover:scale-105 transition">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-100">Company IT Policies</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Rules & security compliance</p>
            </div>
        </div>

        <div onclick="openCategory('asset-policy')" class="bg-slate-900/80 hover:bg-slate-800/80 border border-slate-700/70 p-4 rounded-xl shadow-lg cursor-pointer transition flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-lg border border-purple-500/30 shrink-0 group-hover:scale-105 transition">
                <i class="fa-solid fa-laptop-file"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-100">Asset Guidelines</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Equipment care & return</p>
            </div>
        </div>

        <div onclick="openCategory('faq')" class="bg-slate-900/80 hover:bg-slate-800/80 border border-slate-700/70 p-4 rounded-xl shadow-lg cursor-pointer transition flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg border border-emerald-500/30 shrink-0 group-hover:scale-105 transition">
                <i class="fa-solid fa-comments"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-100">Frequently Asked FAQ</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Quick answers & help</p>
            </div>
        </div>
    </div>

    <!-- MAIN ACCORDION CONTAINER SECTION -->
    <div class="space-y-4">

        <!-- SECTION A: SYSTEM USER GUIDE -->
        <div id="section-guide" class="policy-section bg-slate-900/90 border border-slate-700/80 rounded-xl overflow-hidden shadow-xl">
            <div onclick="toggleAccordion('acc-guide')" class="bg-slate-950/80 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-800/50 transition">
                <h2 class="text-base font-bold text-slate-100 flex items-center gap-2.5">
                    <i class="fa-solid fa-book text-sky-400"></i> A. System User Guide
                </h2>
                <i id="icon-acc-guide" class="fa-solid fa-chevron-down text-slate-400 text-sm transition-transform duration-200"></i>
            </div>

            <div id="acc-guide" class="p-5 space-y-4 text-xs text-slate-300 leading-relaxed border-t border-slate-800">
                <!-- 1. How to Submit a Support Request -->
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <h4 class="font-bold text-sky-300 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus"></i> How to Submit a Support Request
                    </h4>
                    <p class="text-slate-400 mb-1">Follow these steps to report an issue and receive assistance from the IT support team:</p>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-300 pl-1">
                        <li>Open <strong class="text-white">Create Ticket</strong> from the sidebar.</li>
                        <li>Enter a short summary in <strong class="text-white">Issue Title</strong>.</li>
                        <li>Select the affected device or asset by typing asset tag (<strong class="text-cyan-400 font-mono">TKS_LAP_002</strong>) in <strong class="text-white">Select Faulty Device / Asset</strong>.</li>
                        <li>Select the appropriate <strong class="text-white">Category</strong> and <strong class="text-white">Priority</strong>.</li>
                        <li>Describe the issue in detail under <strong class="text-white">Detailed Problem Description</strong>.</li>
                        <li>Attach supporting screenshots or files (optional).</li>
                        <li>Click <strong class="text-white">Submit Ticket</strong>.</li>
                    </ol>
                </div>

                <!-- 2. How to Track & Check Ticket Status (DIKEMASKINI SUPAYA TALLY DENGAN SISTEM SEBENAR) -->
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <h4 class="font-bold text-sky-300 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass font-bold"></i> How to Track & Check Ticket Status
                    </h4>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-300 pl-1">
                        <li>Navigate to <strong class="text-white">Manage Tickets</strong> from the left menu or click <strong class="text-cyan-400">View All Tickets</strong> on the Dashboard.</li>
                        <li>Locate your ticket card and click the blue <span class="bg-blue-600/40 text-blue-300 border border-blue-500/50 px-2 py-0.5 rounded font-bold"><i class="fa-solid fa-eye text-[10px]"></i> View</span> button.</li>
                        <li>Review the current status, assigned technician, target SLA countdown, and conversation log for real-time updates.</li>
                    </ol>
                </div>

                <!-- 3. How to Rate & Submit Feedback -->
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <h4 class="font-bold text-amber-300 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-star"></i> How to Rate & Submit Feedback for Closed Tickets
                    </h4>
                    <p class="text-slate-400 mb-1">Once your issue is resolved and the ticket status is marked as <strong class="text-emerald-400">Closed</strong>, you can rate our service by following these steps:</p>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-300 pl-1">
                        <li>Navigate to <strong class="text-white">Manage Tickets</strong> from the left menu or click <strong class="text-cyan-400">View All Tickets</strong> on the Dashboard.</li>
                        <li>Locate your closed ticket card and click the blue <span class="bg-blue-600/40 text-blue-300 border border-blue-500/50 px-2 py-0.5 rounded font-bold"><i class="fa-solid fa-eye text-[10px]"></i> View</span> button.</li>
                        <li>Scroll down to the bottom feedback section, select your star rating (<strong class="text-amber-300">1 to 5 stars</strong>), enter your comments, and click <strong class="text-white">Submit Feedback</strong>.</li>
                    </ol>
                </div>

                <!-- 4. Ticket Statuses & Priority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                    <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2.5">
                        <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Ticket Status Definitions</h4>
                        <ul class="space-y-2">
                            <li><span class="bg-rose-500/20 text-rose-300 border border-rose-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">Open</span> Submitted & awaiting IT technician review.</li>
                            <li><span class="bg-amber-500/20 text-amber-300 border border-amber-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">In Progress</span> Technician is actively resolving your issue.</li>
                            <li><span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">Closed</span> Issue has been resolved and ticket is completed.</li>
                        </ul>
                    </div>

                    <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2.5">
                        <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Priority Level Definitions</h4>
                        <ul class="space-y-2">
                            <li><span class="bg-sky-500/20 text-sky-300 border border-sky-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">Low</span> Minor issues with no impact on daily work.</li>
                            <li><span class="bg-amber-500/20 text-amber-300 border border-amber-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">Medium</span> Moderate issue with temporary workarounds available.</li>
                            <li><span class="bg-rose-500/20 text-rose-300 border border-rose-500/50 px-2 py-0.5 rounded text-[10px] font-bold mr-1.5">High</span> Severe issue completely blocking critical work tasks.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION B: COMPANY IT POLICIES -->
        <div id="section-it-policy" class="policy-section bg-slate-900/90 border border-slate-700/80 rounded-xl overflow-hidden shadow-xl">
            <div onclick="toggleAccordion('acc-it-policy')" class="bg-slate-950/80 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-800/50 transition">
                <h2 class="text-base font-bold text-slate-100 flex items-center gap-2.5">
                    <i class="fa-solid fa-scroll text-indigo-400"></i> B. Company IT Policies
                </h2>
                <i id="icon-acc-it-policy" class="fa-solid fa-chevron-down text-slate-400 text-sm transition-transform duration-200"></i>
            </div>

            <div id="acc-it-policy" class="p-5 space-y-3 text-xs text-slate-300 leading-relaxed border-t border-slate-800 hidden">
                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-indigo-300 text-xs mb-1">1. Asset Usage & Responsibility</h4>
                    <p class="text-slate-400">Employees are required to exercise reasonable care with company-issued IT equipment. Accidental damage or malfunctions must be reported promptly.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-indigo-300 text-xs mb-1">2. Password & Credential Security</h4>
                    <p class="text-slate-400">Passwords must remain strictly confidential and updated periodically. Never share credentials with external parties or unauthorized colleagues.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-indigo-300 text-xs mb-1">3. Acceptable Internet Usage</h4>
                    <p class="text-slate-400">Company network resources are strictly allocated for official work purposes. Unauthorized downloads or access to restricted portals is strictly prohibited.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-indigo-300 text-xs mb-1">4. Software Installation Control</h4>
                    <p class="text-slate-400">Installing unapproved third-party software on company workstations is prohibited without prior authorization from the IT department.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-indigo-300 text-xs mb-1">5. Data Privacy & Confidentiality</h4>
                    <p class="text-slate-400">Company internal data must not be transferred, copied, or stored on personal devices or unapproved external cloud services.</p>
                </div>
            </div>
        </div>

        <!-- SECTION C: ASSET MANAGEMENT GUIDELINES -->
        <div id="section-asset-policy" class="policy-section bg-slate-900/90 border border-slate-700/80 rounded-xl overflow-hidden shadow-xl">
            <div onclick="toggleAccordion('acc-asset-policy')" class="bg-slate-950/80 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-800/50 transition">
                <h2 class="text-base font-bold text-slate-100 flex items-center gap-2.5">
                    <i class="fa-solid fa-laptop-code text-purple-400"></i> C. Asset Management Guidelines
                </h2>
                <i id="icon-acc-asset-policy" class="fa-solid fa-chevron-down text-slate-400 text-sm transition-transform duration-200"></i>
            </div>

            <div id="acc-asset-policy" class="p-5 space-y-3 text-xs text-slate-300 leading-relaxed border-t border-slate-800 hidden">
                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-purple-300 text-xs mb-1">1. Equipment Assignment</h4>
                    <p class="text-slate-400">Every IT asset is assigned to a single registered user for security tracking and inventory auditing purposes.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-purple-300 text-xs mb-1">2. Hardware Maintenance & Return</h4>
                    <p class="text-slate-400">Assigned hardware must be handed over to the IT department for scheduled maintenance or upon employee offboarding/resignation.</p>
                </div>

                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                    <h4 class="font-bold text-purple-300 text-xs mb-1">3. Lost or Stolen Equipment Reporting</h4>
                    <p class="text-slate-400">If an assigned device is lost or stolen, log a Critical support ticket immediately and file a report with company security.</p>
                </div>
            </div>
        </div>

        <!-- SECTION D: FREQUENTLY ASKED QUESTIONS (FAQ) -->
        <div id="section-faq" class="policy-section bg-slate-900/90 border border-slate-700/80 rounded-xl overflow-hidden shadow-xl">
            <div onclick="toggleAccordion('acc-faq')" class="bg-slate-950/80 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-800/50 transition">
                <h2 class="text-base font-bold text-slate-100 flex items-center gap-2.5">
                    <i class="fa-solid fa-comments text-emerald-400"></i> D. Frequently Asked Questions (FAQ)
                </h2>
                <i id="icon-acc-faq" class="fa-solid fa-chevron-down text-slate-400 text-sm transition-transform duration-200" style="transform: rotate(180deg);"></i>
            </div>

            <div id="acc-faq" class="p-5 space-y-4 text-xs text-slate-300 leading-relaxed border-t border-slate-800">
                
                <!-- Filter Pills Badges -->
                <div class="flex flex-wrap gap-2 pb-2 border-b border-slate-800">
                    <button onclick="filterFaq('all')" class="faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 transition active-faq-btn" data-filter="all">All FAQs</button>
                    <button onclick="filterFaq('feature')" class="faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 hover:text-white transition" data-filter="feature">System Features</button>
                    <button onclick="filterFaq('selfservice')" class="faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 hover:text-white transition" data-filter="selfservice">Self-Service & Fixes</button>
                    <button onclick="filterFaq('sla')" class="faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 hover:text-white transition" data-filter="sla">SLA & Escalation</button>
                </div>

                <!-- FAQ ITEMS CONTAINER (Individual Accordions) -->
                <div class="space-y-3">

                    <!-- FAQ 1 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="feature">
                        <button onclick="toggleFaqItem('faq-item-1')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[System Feature]</span>
                                <span class="font-bold text-slate-200 text-xs">How does the Automated Technician Assignment (Round-Robin) work in this platform?</span>
                            </div>
                            <i id="icon-faq-item-1" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-1" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            When you submit a ticket, the system automatically assigns it to an available technician using a fair distribution algorithm. You don't need to manually pick a technician; the assigned engineer will be notified immediately via system email and dashboard alerts.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="feature">
                        <button onclick="toggleFaqItem('faq-item-2')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-purple-500/20 text-purple-300 border border-purple-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[IT Asset]</span>
                                <span class="font-bold text-slate-200 text-xs">How do I link my IT Asset (Laptop/Device) directly when creating a ticket?</span>
                            </div>
                            <i id="icon-faq-item-2" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-2" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            On the <strong>Create Ticket</strong> page, click the <em>Select Faulty Device / Asset</em> search bar. Type your asset tag (<code class="text-cyan-400 font-mono">TKS_LAP_002</code>) or device model to link it. Linking your asset helps technicians view your hardware specs instantly without asking for manual details.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="feature">
                        <button onclick="toggleFaqItem('faq-item-3')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-blue-500/20 text-blue-300 border border-blue-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[Attachment]</span>
                                <span class="font-bold text-slate-200 text-xs">Can I attach error screenshots or diagnostic logs to my support ticket?</span>
                            </div>
                            <i id="icon-faq-item-3" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-3" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            Yes. You can drag and drop image files (PNG, JPG, JPEG up to 5MB) into the attachment dropzone on the <strong>Create Ticket</strong> page. Attaching clear error popups or logs speeds up issue resolution by up to 50%.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="feature">
                        <button onclick="toggleFaqItem('faq-item-4')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[Live Chat]</span>
                                <span class="font-bold text-slate-200 text-xs">How do I track real-time updates and chat with the assigned technician?</span>
                            </div>
                            <i id="icon-faq-item-4" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-4" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            Open your ticket from the <strong>Manage Tickets</strong> or <strong>Dashboard</strong> page to access the Live Discussion Panel. You can exchange messages, provide additional information, and receive live status notifications without needing to send separate emails.
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="selfservice">
                        <button onclick="toggleFaqItem('faq-item-5')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-amber-500/20 text-amber-300 border border-amber-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[Network]</span>
                                <span class="font-bold text-slate-200 text-xs">What should I check before submitting an "Internet / Wi-Fi Disconnected" ticket?</span>
                            </div>
                            <i id="icon-faq-item-5" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-5" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            First, verify the <strong>Status & IT Announcement</strong> widget on your Dashboard to check if there is an ongoing network outage. If all systems are operational, try toggling your device's Wi-Fi off/on or forgetting the corporate SSID before opening a ticket.
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="selfservice">
                        <button onclick="toggleFaqItem('faq-item-6')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[Self-Service]</span>
                                <span class="font-bold text-slate-200 text-xs">How can I resolve minor software crashes or freezing without waiting for IT?</span>
                            </div>
                            <i id="icon-faq-item-6" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-6" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            Save your work, open Task Manager (<kbd class="bg-slate-800 px-1.5 py-0.5 rounded text-[10px]">Ctrl</kbd> + <kbd class="bg-slate-800 px-1.5 py-0.5 rounded text-[10px]">Shift</kbd> + <kbd class="bg-slate-800 px-1.5 py-0.5 rounded text-[10px]">Esc</kbd>), end the unresponsive application process, and reboot your machine. Over 70% of temporary memory leaks and software freezes are resolved by a system restart.
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="selfservice">
                        <button onclick="toggleFaqItem('faq-item-7')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-teal-500/20 text-teal-300 border border-teal-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[Knowledge Base]</span>
                                <span class="font-bold text-slate-200 text-xs">Where can I find standard software installation guides and company IT policies?</span>
                            </div>
                            <i id="icon-faq-item-7" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-7" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            Navigate to the <strong>Policies & Knowledge</strong> page on the sidebar navigation menu. You will find step-by-step documentation, software download portals, and IT compliance guidelines for self-guided troubleshooting.
                        </div>
                    </div>

                    <!-- FAQ 8 -->
                    <div class="faq-item bg-slate-950/60 rounded-xl border border-slate-800/90 overflow-hidden" data-category="sla">
                        <button onclick="toggleFaqItem('faq-item-8')" class="w-full text-left p-3.5 flex justify-between items-center gap-3 hover:bg-slate-800/40 transition">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="bg-rose-500/20 text-rose-300 border border-rose-500/40 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">[SLA & Priority]</span>
                                <span class="font-bold text-slate-200 text-xs">What are the target resolution times (SLA) based on ticket priority levels?</span>
                            </div>
                            <i id="icon-faq-item-8" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200 shrink-0"></i>
                        </button>
                        <div id="faq-item-8" class="hidden px-4 pb-4 pt-1 text-slate-400 text-xs border-t border-slate-800/50 bg-slate-950/40">
                            Response and resolution times are governed by ITIL standards:
                            <ul class="list-disc list-inside mt-1.5 space-y-1 text-slate-300 pl-1">
                                <li><strong class="text-rose-400">High / Critical:</strong> Initial response within 3 hours (Work-stopping/Production down).</li>
                                <li><strong class="text-amber-400">Medium:</strong> Initial response within 12 hours (Standard business impact).</li>
                                <li><strong class="text-sky-400">Low:</strong> Initial response within 24 hours (Minor requests/Inquiries).</li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // Toggle Utama Accordion Seksyen (A, B, C, D)
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    // Toggle Item FAQ Individu di dalam Seksyen D
    function toggleFaqItem(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    // Fungsi Filter Pill Badges FAQ
    function filterFaq(category) {
        const items = document.querySelectorAll('.faq-item');
        const buttons = document.querySelectorAll('.faq-filter-btn');

        buttons.forEach(btn => {
            if (btn.getAttribute('data-filter') === category) {
                btn.className = "faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 transition active-faq-btn";
            } else {
                btn.className = "faq-filter-btn px-3 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 hover:text-white transition";
            }
        });

        items.forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function openCategory(type) {
        const targetId = 'acc-' + type;
        const targetSection = document.getElementById('section-' + type);
        
        const content = document.getElementById(targetId);
        if (content && content.classList.contains('hidden')) {
            toggleAccordion(targetId);
        }

        if (targetSection) {
            targetSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function searchPolicies() {
        const input = document.getElementById('policySearchInput').value.toLowerCase();
        const sections = document.querySelectorAll('.policy-section');

        sections.forEach(sec => {
            const text = sec.innerText.toLowerCase();
            if (text.includes(input)) {
                sec.style.display = '';
                const accContent = sec.querySelector('[id^="acc-"]');
                if (accContent && input.trim() !== '') {
                    accContent.classList.remove('hidden');
                }
            } else {
                sec.style.display = 'none';
            }
        });
    }
</script>
@endsection