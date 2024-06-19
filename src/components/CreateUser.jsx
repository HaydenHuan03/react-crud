import React, { useState } from 'react'
import axios from 'axios'
import { Navigate, useNavigate } from 'react-router-dom';

function CreateUser() {
    const Navigate = useNavigate();
    
    const[inputs, setInputs] = useState({
        name: '',
        email: '',
        mobile: ''
    });;

    const handleSubmit = (event) => {
        event.preventDefault()

        axios.post('http://localhost:80/api/user/save', inputs).then(function(response){
            console.log(response.data)
            Navigate('/')
        })

    }

    const handleChange = (event) => {
        const name = event.target.name;
        const value = event.target.value;
        setInputs(values => ({...values, [name]:value}))
    }

  return (
    <div>
        <h1>Create Users</h1>
        <form onSubmit={handleSubmit} action="">
            <table cellSpacing="10">
                <tbody>
                    <tr>
                        <th>
                        <label htmlFor="">Name: </label>
                        </th>
                        <td>
                        <input type="text" name='name' onChange={handleChange}/>
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label htmlFor="">Email: </label>
                        </th>
                        <td>
                        <input type="text" name='email' onChange={handleChange}/>
                        <br />                            
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label htmlFor="">Mobile: </label>
                        </th>
                        <td>
                        <input type="text" name='mobile' onChange={handleChange}/>                            
                        </td>
                    </tr>

                    <tr>
                        <td colSpan='2' align='right'>
                        <button>Save</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
  )
}

export default CreateUser