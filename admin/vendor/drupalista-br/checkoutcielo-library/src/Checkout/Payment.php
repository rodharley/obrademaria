<?php

namespace CieloCheckout;

class Payment extends Commons {

  public
    $BoletoDiscount,
    $DebitDiscount,
    $MaxNumberOfInstallments;

  protected function validate() {
    $this->BoletoDiscount_validate();
    $this->DebitDiscount_validate();
    $this->MaxNumberOfInstallments_validate();
  }

  private function BoletoDiscount_validate() {
    if (!empty($this->BoletoDiscount)) {
      if (!is_int($this->BoletoDiscount) || $this->BoletoDiscount > 100 || $this->BoletoDiscount < 0) {
        throw new \Exception("'BoletoDiscount' must be an integer between 0 and 100.");
      }
    }
  }
  private function MaxNumberOfInstallments_validate() {
    if (!empty($this->MaxNumberOfInstallments)) {
      if (!is_int($this->MaxNumberOfInstallments) || $this->MaxNumberOfInstallments > 100 || $this->MaxNumberOfInstallments < 0) {
        throw new \Exception("'MaxNumberOfInstallments' must be an integer between 0 and 100.");
      }
    }
  }

  private function DebitDiscount_validate() {
    if (!empty($this->DebitDiscount)) {
      if (!is_int($this->DebitDiscount) || $this->DebitDiscount > 100 || $this->DebitDiscount < 0) {
        throw new \Exception("'DebitDiscount' must be an integer between 0 and 100.");
      }
    }
  }
}
