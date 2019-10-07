import React, { Component } from 'react';

export default class LoadModal extends Component {
  render () {
    return (
      <div className="modal fade" id="loadModalDiv" tabIndex="-1" role="dialog" aria-labelledby="loadModalLabel" aria-hidden="true" data-backdrop="static">
        <div className="modal-dialog modal-dialog-centered" role="document">
          <div className="modal-content">
            <div className="modal-body text-center">
              <h5 className="modal-title" id="loadModalLabel">Traitement en cours...</h5>
              <i className="fas fa-spinner fa-pulse"></i>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
