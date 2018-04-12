<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer;

interface CompanyUserInvitationFacadeInterface
{
    /**
     * Specification:
     * - Imports company user invitations to the persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer
     */
    public function importInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportReportTransfer;

    /**
     * Specification:
     * - Sends all invitations that match the defined criteria
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer
     */
    public function sendInvitations(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationAffectedReportTransfer;

    /**
     * Specification:
     * - Deletes all invitations that match the defined criteria
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer
     */
    public function deleteInvitations(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationAffectedReportTransfer;

    /**
     * Specification:
     * - Retrieves company users invitation collection by company user id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationCollectionTransfer;

    /**
     * @api
     *
     * @return void
     */
    public function install(): void;
}
