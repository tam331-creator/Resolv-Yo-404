<?php

namespace App\Helpers;

class ActivityHelper
{
    /**
     * Peta Lengkap 49 Emoji Aktiviti Sistem
     */
    public static function getEmojiMap(): array
    {
        return [
            // --- TIKET & AKAUN ACTIVITIES (10) ---
            'ticket_created'            => '🎫',
            'ticket_assigned'           => '🎟️',
            'ticket_status_updated'     => '📌',
            'ticket_updated'            => '✏️',
            'ticket_replied'            => '💬',
            'ticket_reopened'           => '🔁',
            'ticket_resolved'           => '✅',
            'ticket_closed'             => '❌',
            'password_changed'          => '🔑',
            'user_login'                => '👤',

            // --- ASSET / ITAM ACTIVITIES (9) ---
            'asset_created'             => '💻',
            'asset_assigned'            => '🖥',
            'asset_reassigned'          => '🔄',
            'asset_registered'          => '📦',
            'asset_updated'             => '✏️',
            'asset_deleted'             => '🗑',
            'asset_retired'             => '♻️',
            'asset_maintenance'         => '🔧',
            'asset_returned_active'     => '✅',

            // --- WARRANTY ACTIVITIES (4) ---
            'warranty_updated'          => '📅',
            'warranty_expiring_soon'    => '⚠️',
            'warranty_expired'          => '❌',
            'warranty_renewed'          => '✅',

            // --- DEPARTMENT ACTIVITIES (2) ---
            'department_changed'        => '🏢',
            'user_moved_department'     => '👨‍💼',

            // --- LOCATION ACTIVITIES (2) ---
            'office_location_updated'   => '📍',
            'asset_relocated'           => '🚚',

            // --- SYSTEM ACTIVITIES (1) ---
            'system_deployed'           => '🚀',

            // --- AI ACTIVITIES (2) ---
            'ai_suggestion_generated'   => '🤖',
            'ai_resolution_created'     => '🪄',

            // --- KNOWLEDGE BASE ACTIVITIES (4) ---
            'policy_created'            => '📚',
            'knowledge_article_updated' => '📄',
            'new_article_published'     => '📝',
            'policy_deleted'            => '🗑',

            // --- KESELAMATAN & LAIN-LAIN SOKONGAN (15) ---
            'security_locked'           => '🔒',
            'security_unlocked'         => '🔐',
            'sla_warning'               => '⏱️',
            'analytics_viewed'          => '📊',
            'announcement_posted'       => '📢',
            'system_settings_updated'   => '⚙️',
            'service_stopped'           => '🛑',
            'system_repaired'           => '🛠️',
            'target_achieved'           => '🎯',
            'idea_submitted'            => '💡',
            'report_exported'           => '📤',
            'data_imported'             => '📥',
            'document_attached'         => '📄',
            'sync_completed'            => '🔄',
            'backup_created'            => '🚀',
        ];
    }

    /**
     * Dapatkan Emoji Mengikut Jenis Aktiviti
     */
    public static function getEmoji(string $type): string
    {
        $map = self::getEmojiMap();
        return $map[$type] ?? '📌';
    }
}