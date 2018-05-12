import React, {Component} from 'react';
import axios, { post } from 'axios';
import '../../css/app.scss';

const serviceBase = window.location.pathname.split('/dist')[0];

class Upload extends Component {

    constructor(props) {
      super(props);
      this.state ={
        file:null
      }
      this.onFormSubmit = this.onFormSubmit.bind(this)
      this.onChange = this.onChange.bind(this)
      this.fileUpload = this.fileUpload.bind(this)
    }
    onFormSubmit(e){
      e.preventDefault() // Stop form submit
      this.fileUpload(this.state.file).then((response)=>{
        console.log(response.data);
      })
    }
    onChange(e) {
      this.setState({file:e.target.files[0]})
    }
    fileUpload(file){
      console.log('file: ', file);
      const url = serviceBase + '/services/upload/';

      const formData = new FormData();
      formData.append('file',file)
      formData.append('user','zach@zach.com');
      formData.append('ctype', file.type);
      formData.append('cname', file.name);
      console.log(formData);
      const config = {
          headers: {
              'content-type': 'multipart/form-data'
          }
      }
      return post(url, formData, config);
    }

    render(){
      return (
        <div>
          <h1>Upload</h1>
          <form onSubmit={this.onFormSubmit}>
            <input type="file" onChange={this.onChange} />
            <button type="submit" className="btn">Upload</button>
          </form>
        </div>
      );
    }
}

export default Upload
